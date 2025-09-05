<?php

return [
    /* Configurations for application */
    'role' => [
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
            'view.pokdarwis.report',
            'view.bumdes.report',

            'manage.pokdarwis.business',
            'manage.bumdes.business',
        ],
    ],

    /* Permissions for each role */
    'permissions' => [
        'admin' => 'all',
        'pokdarwis' => [
            'view.pokdarwis.report',
            'manage.pokdarwis.business',
        ],
        'bumdes' => [
            'view.pokdarwis.report',
            'view.bumdes.report',
            'manage.pokdarwis.business',
            'manage.bumdes.business',
        ],
        'bpd' => [
            'view.pokdarwis.report',
            'view.bumdes.report',
            'manage.pokdarwis.business',
            'manage.bumdes.business',
        ],
    ],
];
