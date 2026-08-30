<?php

return [
    'user_model' => env('SOCIAL_NETWORK_USER_MODEL'),
    'default_visibility' => 'public',
    'visibilities' => ['public', 'followers', 'private'],
    'verification_statuses' => ['unverified', 'pending', 'verified', 'rejected'],
    'lifecycle_states' => ['active', 'suspended', 'deleted'],
    'maximum_attributes' => 32,
    'maximum_attribute_value_length' => 2048,
];
