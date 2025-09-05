<?php

return [
    /* Configurations for application */
    'role' => [
        // Default role assigned to users when they are created
        // This should match the default role in your RBAC configuration
        'default' => 'pokdarwis',

        // Highest role in the hierarchy, typically for administrators
        // This should match the highest role in your RBAC configuration
        // It is used to determine the highest level of access in the system
        'highest' => 'admin',
    ],

    /* List of roles and permissions */
    'list' => [
        'roles' => [
            'admin',
            'pokdarwis',
            'bumdes',
            'bpd'
        ],
        'permissions' => [

        ],
    ],

    /* Permissions for each role */
    'permissions' => [
        'admin' => 'all',
        'pokdarwis' => [
            // Define specific permissions for the 'pokdarwis' role here
        ],
        'bumdes' => [
            // Define specific permissions for the 'bumdes' role here
        ],
        'bpd' => [
            // Define specific permissions for the 'bpd' role here
        ],
    ],
];
