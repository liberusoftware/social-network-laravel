<?php

namespace App\Support;

use Filament\Panel;
use ReflectionProperty;

final class PanelNavigation
{
    public function configure(Panel $panel): void
    {
        $groups = $panel->getId() === 'admin' ? $this->adminGroups() : $this->appGroups();

        foreach ($groups as $class => $group) {
            if (class_exists($class)) {
                $property = new ReflectionProperty($class, 'navigationGroup');
                $property->setValue(null, $group);
            }
        }
    }

    /** @return array<class-string, string> */
    private function appGroups(): array
    {
        return [
            'Liberu\\SocialNetwork\\Analytics\\Filament\\Pages\\Dashboard' => 'Workspace',
            'Liberu\\SocialNetwork\\Communities\\Filament\\Pages\\Communities' => 'Explore',
            'Liberu\\SocialNetwork\\Discovery\\Filament\\Pages\\Search' => 'Explore',
            'Liberu\\SocialNetwork\\Engagement\\Filament\\Pages\\Engagements' => 'Engage',
            'Liberu\\SocialNetwork\\Events\\Filament\\Pages\\Events' => 'Engage',
            'Liberu\\SocialNetwork\\Feed\\Filament\\Pages\\Entries' => 'Explore',
            'Liberu\\SocialNetwork\\Media\\Filament\\Pages\\Assets' => 'Publish',
            'Liberu\\SocialNetwork\\Messaging\\Filament\\Pages\\Conversations' => 'Engage',
            'Liberu\\SocialNetwork\\Moderation\\Filament\\Pages\\Reports' => 'Moderation',
            'Liberu\\SocialNetwork\\Notifications\\Filament\\Pages\\Notifications' => 'Engage',
            'Liberu\\SocialNetwork\\Profiles\\Filament\\Pages\\EditProfile' => 'Account',
            'Liberu\\SocialNetwork\\Publishing\\Filament\\Pages\\Publications' => 'Publish',
            'Liberu\\SocialNetwork\\SocialCore\\Filament\\Pages\\SocialCoreSettings' => 'Settings',
            'Liberu\\SocialNetwork\\SocialGraph\\Filament\\Pages\\Blocks' => 'Explore',
            'Liberu\\SocialNetwork\\SocialGraph\\Filament\\Pages\\Lists' => 'Explore',
            'Liberu\\SocialNetwork\\SocialGraph\\Filament\\Pages\\Relationships' => 'Explore',
            'Liberu\\SocialNetwork\\SocialGraph\\Filament\\Pages\\Suggestions' => 'Explore',
        ];
    }

    /** @return array<class-string, string> */
    private function adminGroups(): array
    {
        return [
            'Liberu\\Foundation\\IdentityFilament\\Resources\\UserResource' => 'People & Access',
            'Liberu\\Foundation\\OrganizationsFilament\\Resources\\TeamResource' => 'People & Access',
            'BezhanSalleh\\FilamentShield\\Resources\\Roles\\RoleResource' => 'People & Access',
            'Liberu\\Foundation\\ModuleManagerFilament\\Pages\\FoundationOperations' => 'Operations',
            'Liberu\\Foundation\\SettingsFilament\\Pages\\ManageSiteSettings' => 'Settings',
        ];
    }
}
