<?php

return [
    // Hosts must provide their tenant/team model. Keeping this unset makes a
    // misconfigured host fail closed instead of coupling the package to App\\.
    'team_model' => env('SOCIAL_NETWORK_TEAM_MODEL'),
    'default_deployment_mode' => 'hosted',
    'allowed_deployment_modes' => ['hosted', 'self_hosted', 'federated'],
    'maximum_payload_keys' => 64,
    'maximum_payload_depth' => 4,
];
