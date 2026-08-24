<?php

return [
    'user_model' => env('SOCIAL_NETWORK_USER_MODEL', 'App\\Models\\User'),
    'default_visibility' => 'public',
    'visibilities' => ['public', 'followers', 'private'],
    'verification_statuses' => ['unverified', 'pending', 'verified', 'rejected'],
    'lifecycle_states' => ['active', 'suspended', 'deleted'],
];
