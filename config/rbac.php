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
        ],
        'permissions' => [
            // General Permissions
            'view.pokdarwis.report',
            'view.bumdes.report',

            'manage.pokdarwis.business',
            'manage.bumdes.business',

            // Pokdarwis Permissions
            'pokdarwis.tourist_ticket.viewAny',
            'pokdarwis.tourist_ticket.create',
            'pokdarwis.tourist_ticket.update',
            'pokdarwis.tourist_ticket.delete',

            'pokdarwis.river_tubing.viewAny',
            'pokdarwis.river_tubing.create',
            'pokdarwis.river_tubing.update',
            'pokdarwis.river_tubing.delete',

            'pokdarwis.stall_rental.viewAny',
            'pokdarwis.stall_rental.create',
            'pokdarwis.stall_rental.update',
            'pokdarwis.stall_rental.delete',

            'pokdarwis.umkm_rental.viewAny',
            'pokdarwis.umkm_rental.create',
            'pokdarwis.umkm_rental.update',
            'pokdarwis.umkm_rental.delete',

            'pokdarwis.fish_feed.viewAny',
            'pokdarwis.fish_feed.create',
            'pokdarwis.fish_feed.update',
            'pokdarwis.fish_feed.delete',

            // Bumdes Permissions
            'bumdes.restaurant_rental.viewAny',
            'bumdes.restaurant_rental.create',
            'bumdes.restaurant_rental.update',
            'bumdes.restaurant_rental.delete',

            'bumdes.internet_rental.viewAny',
            'bumdes.internet_rental.create',
            'bumdes.internet_rental.update',
            'bumdes.internet_rental.delete',
        ],
    ],

    /* Permissions for each role */
    'permissions' => [
        'admin' => 'all',
        'pokdarwis' => [
            'view.pokdarwis.report',

            'manage.pokdarwis.business',

            'pokdarwis.tourist_ticket.viewAny',
            'pokdarwis.tourist_ticket.create',
            'pokdarwis.tourist_ticket.update',
            'pokdarwis.tourist_ticket.delete',

            'pokdarwis.river_tubing.viewAny',
            'pokdarwis.river_tubing.create',
            'pokdarwis.river_tubing.update',
            'pokdarwis.river_tubing.delete',

            'pokdarwis.stall_rental.viewAny',
            'pokdarwis.stall_rental.create',
            'pokdarwis.stall_rental.update',
            'pokdarwis.stall_rental.delete',

            'pokdarwis.umkm_rental.viewAny',
            'pokdarwis.umkm_rental.create',
            'pokdarwis.umkm_rental.update',
            'pokdarwis.umkm_rental.delete',

            'pokdarwis.fish_feed.viewAny',
            'pokdarwis.fish_feed.create',
            'pokdarwis.fish_feed.update',
            'pokdarwis.fish_feed.delete',
        ],
        'bumdes' => [
            'view.pokdarwis.report',
            'view.bumdes.report',

            'manage.pokdarwis.business',
            'manage.bumdes.business',

            'pokdarwis.tourist_ticket.viewAny',
            'pokdarwis.tourist_ticket.create',
            'pokdarwis.tourist_ticket.update',
            'pokdarwis.tourist_ticket.delete',

            'pokdarwis.river_tubing.viewAny',
            'pokdarwis.river_tubing.create',
            'pokdarwis.river_tubing.update',
            'pokdarwis.river_tubing.delete',

            'pokdarwis.stall_rental.viewAny',
            'pokdarwis.stall_rental.create',
            'pokdarwis.stall_rental.update',
            'pokdarwis.stall_rental.delete',

            'pokdarwis.umkm_rental.viewAny',
            'pokdarwis.umkm_rental.create',
            'pokdarwis.umkm_rental.update',
            'pokdarwis.umkm_rental.delete',

            'pokdarwis.fish_feed.viewAny',
            'pokdarwis.fish_feed.create',
            'pokdarwis.fish_feed.update',
            'pokdarwis.fish_feed.delete',

            'bumdes.restaurant_rental.viewAny',
            'bumdes.restaurant_rental.create',
            'bumdes.restaurant_rental.update',
            'bumdes.restaurant_rental.delete',

            'bumdes.internet_rental.viewAny',
            'bumdes.internet_rental.create',
            'bumdes.internet_rental.update',
            'bumdes.internet_rental.delete',
        ],
    ],
];
