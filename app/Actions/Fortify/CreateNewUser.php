<?php

namespace App\Actions\Fortify;

use App\Models\User as ApplicationUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Liberu\Foundation\Identity\Contracts\InvitationValidator;
use Liberu\Foundation\Identity\Contracts\RegistrationPolicy;
use Liberu\Foundation\Identity\Events\IdentityEvent;
use Liberu\Foundation\Identity\Support\IdentifierNormalizer;
use Liberu\Foundation\JetstreamBridge\Actions\Fortify\PasswordValidationRules;

final class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        private readonly RegistrationPolicy $registration,
        private readonly InvitationValidator $invitations,
        private readonly IdentifierNormalizer $normalizer,
    ) {}

    /**
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $input['email'] = $this->normalizer->email($input['email'] ?? '');

        if (! $this->registration->permitsSelfRegistration()
            || ($this->registration->requiresInvitation() && ! $this->invitations->valid($input['email'], $input['invitation_token'] ?? null))) {
            throw ValidationException::withMessages(['email' => [__('Registration is not available for this request.')]]);
        }

        /** @var class-string<ApplicationUser> $userModel */
        $userModel = config('auth.providers.users.model');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($userModel)],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = DB::transaction(function () use ($userModel, $input): ApplicationUser {
            /** @var ApplicationUser $user */
            $user = $userModel::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $team = $user->ownedTeams()->create([
                'name' => $user->name."'s Team",
                'personal_team' => true,
            ]);

            $user->forceFill(['current_team_id' => $team->getKey()])->save();

            return $user;
        });

        event(new IdentityEvent('identity.registered', $user->getAuthIdentifier()));

        return $user;
    }
}
