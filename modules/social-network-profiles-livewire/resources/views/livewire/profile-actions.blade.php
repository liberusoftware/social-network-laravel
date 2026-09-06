<div class="space-y-3">
    <label for="profile-id" class="block text-sm font-medium">Profile ID</label>
    <input id="profile-id" type="text" wire:model="profileId" class="rounded border" />
    <div class="flex gap-2">
        <button type="button" wire:click="block" wire:loading.attr="disabled" class="rounded bg-red-600 px-3 py-2 text-white">Block</button>
        <button type="button" wire:click="unblock" wire:loading.attr="disabled" class="rounded border px-3 py-2">Unblock</button>
    </div>
    <label for="lifecycle-state" class="block text-sm font-medium">Lifecycle state</label>
    <select id="lifecycle-state" wire:model="lifecycleState" class="rounded border">
        @foreach (config('social-network-profiles.lifecycle_states') as $state)
            <option value="{{ $state }}">{{ ucfirst($state) }}</option>
        @endforeach
    </select>
    <button type="button" wire:click="updateLifecycle" wire:loading.attr="disabled" class="rounded border px-3 py-2">Update lifecycle</button>
    <label for="verification-status" class="block text-sm font-medium">Verification status</label>
    <select id="verification-status" wire:model="verificationStatus" class="rounded border">
        @foreach (config('social-network-profiles.verification_statuses') as $status)
            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button type="button" wire:click="updateVerification" wire:loading.attr="disabled" class="rounded border px-3 py-2">Update verification</button>
</div>
