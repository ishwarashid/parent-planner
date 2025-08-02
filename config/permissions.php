<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Roles and Permissions
    |--------------------------------------------------------------------------
    |
    | This file defines the permissions for each role in the application.
    | Policies will check against these permissions instead of checking
    | the role directly, allowing for a more flexible authorization system.
    |
    */
    'roles' => [
        'parent' => [
            'children.create', 'children.view', 'children.update', 'children.delete',
            'visitations.create', 'visitations.view', 'visitations.update', 'visitations.delete',
            'expenses.create', 'expenses.view', 'expenses.update', 'expenses.delete',
            'documents.create', 'documents.view', 'documents.update', 'documents.delete',
            'invitations.create', 'invitations.view', 'invitations.delete',
            'billing.view',
        ],
        'co-parent' => [
            'children.create', 'children.view', 'children.update', 'children.delete',
            'visitations.create', 'visitations.view', 'visitations.update', 'visitations.delete',
            'expenses.create', 'expenses.view', 'expenses.update', 'expenses.delete',
            'documents.create', 'documents.view', 'documents.update', 'documents.delete',
            'invitations.create', 'invitations.view', 'invitations.delete',
        ],
        'nanny' => [
            'visitations.view',
        ],
        'guardian' => [
            'visitations.view',
        ],
        'grandparent' => [
            'visitations.view',
        ],
    ],
];
