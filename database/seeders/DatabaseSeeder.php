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

        // Main Parent role with all permissions
        $mainParentRole = Role::updateOrCreate(['name' => 'Main Parent']);
        $mainParentRole->givePermissionTo(Permission::all());

        // Invited User role with specific view permissions
        $invitedUserRole = Role::updateOrCreate(['name' => 'Invited User']);
        $invitedUserRole->givePermissionTo(['view-children', 'view-events', 'view-visitations']);

        // Admin Co-Parent role with all permissions except for invitations
        $adminCoParentRole = Role::updateOrCreate(['name' => 'Admin Co-Parent']);
        $permissionsWithoutInvitations = Permission::where('name', 'NOT LIKE', '%-invitations')->get();
        $adminCoParentRole->syncPermissions($permissionsWithoutInvitations);

        // Co-Parent role with specific permissions for children, events, and all of expenses
        $coParentRole = Role::updateOrCreate(['name' => 'Co-Parent']);
        $coParentRole->givePermissionTo([
            'view-children',
            'view-events',
            'view-expenses',
            'create-expenses',
            'update-expenses',
            'delete-expenses',
        ]);
        
        // Seed help videos
        $this->call(HelpVideoSeeder::class);
    }
}
