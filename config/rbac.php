<?php

return [
    /* Configurations for application */
    'role' => [
        // Highest role in the hierarchy, typically for administrators
        // This should match the highest role in your RBAC configuration
        // It is used to determine the highest level of access in the system
        'highest' => 'admin',

        // Default role assigned to new users or entities
        // This role should have the most basic permissions
        'default' => 'pokdarwis',
    ],

    /* List of roles and permissions */
    'list' => [
        'roles' => [
            'admin',
            'pemdes',
            'pokdarwis',
            'bumdes',
        ],
        'permissions' => [
            // General Permissions
            'view.pokdarwis.report',
            'view.bumdes.report',

            'manage.pokdarwis.business',
            'manage.bumdes.business',
        ],
        'dynamic_permissions' => [
            'pokdarwis' => [
                'tiket-wisata',
                'river-tubing',
                'sewa-warung',
                'sewa-umkm',
                'pakan-ikan',
            ],
            'bumdes' => [
                'sewa-resto',
                'sewa-internet',
            ],
        ],
        'crud_permissions' => [
            'viewAny',
            'view',
            'store',
            'update',
            'delete',
        ],
    ],

    /* Permissions for each role */
    'permissions' => [
        'admin' => 'all',
        'pemdes' => [
            'view.pokdarwis.report',
            'view.bumdes.report',
        ],
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
    ],
];
