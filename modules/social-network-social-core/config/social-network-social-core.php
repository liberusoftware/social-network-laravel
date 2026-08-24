<?php

return [
    'team_model' => env('SOCIAL_NETWORK_TEAM_MODEL', 'App\\Models\\Team'),
    'default_deployment_mode' => 'hosted',
    'allowed_deployment_modes' => ['hosted', 'self_hosted', 'federated'],
];
