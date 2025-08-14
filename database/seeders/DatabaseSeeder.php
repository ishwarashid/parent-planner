<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $permissions = [
            'children' => ['view', 'create', 'update', 'delete'],
            'documents' => ['view', 'create', 'update', 'delete'],
            'events' => ['view', 'create', 'update', 'delete'],
            'expenses' => ['view', 'create', 'update', 'delete'],
            'visitations' => ['view', 'create', 'update', 'delete'],
            'invitations' => ['view', 'create', 'update', 'delete'],
            'reports' => ['view', 'view-calendar'],
        ];

        foreach ($permissions as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(['name' => "{$action}-{$resource}"], ['name' => "{$action}-{$resource}"]);
            }
        }

        $mainParentRole = Role::updateOrCreate(['name' => 'Main Parent']);
        $mainParentRole->givePermissionTo(Permission::all());

        // Create Invited User Role with default permissions
        $invitedUserRole = Role::updateOrCreate(['name' => 'Invited User']);
        $invitedUserRole->givePermissionTo(['view-children', 'view-events']);

        $adminCoParentRole = Role::updateOrCreate(['name' => 'Admin Co-Parent']);
        $adminCoParentRole->givePermissionTo(Permission::all()); // Give them all permissions
    }
}
