<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $entities = ['customers', 'suppliers', 'safes', 'categories', 'items'];
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::findOrCreate($action . ' ' . $entity);
            }
        }

        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        // Assign to first user if exists
        $user = User::first();
        if($user) {
            $user->assignRole('admin');
        }
    }
}
