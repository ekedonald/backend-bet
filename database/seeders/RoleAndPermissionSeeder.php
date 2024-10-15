<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AppConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        // Seed roles
        $adminRole = Role::create(['name' => AppConfig::ADMIN]);
        $customerRole = Role::create(['name' => AppConfig::CUSTOMER]);
        $editorRole = Role::create(['name' => AppConfig::EDITOR]);

        // Seed permissions for admin role
        $adminPermissions = [
            'edit_user', 'add_user', 'view_user', 'update_user', 'delete_user',
            'edit_role', 'add_role', 'view_role', 'update_role', 'delete_role',
            'edit_permission', 'add_permission', 'view_permission', 'update_permission', 'delete_permission',
        ];
        foreach ($adminPermissions as $permission) {
            $this->createPermission($permission, $adminRole);
        }

        // Seed permissions for customer role
        $customerPermissions = [
            'create_bet', 'view_bet', 'view_pools', 'view_pool', 'deposit',
            'withdraw',
        ];
        foreach ($customerPermissions as $permission) {
            $this->createPermission($permission, $customerRole);
        }

        // Seed permissions for editor role
        $editorPermissions = [
            'create_bet', 'view_bet', 'view_pools', 'view_pool', 'deposit',
            'withdraw',
        ];
        foreach ($editorPermissions as $permission) {
            $this->createPermission($permission, $editorRole);
        }

        $user = User::create([
            'name' => 'Sonawap',
            'email' => 'codeitmi@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole($adminRole);
    }

    private function createPermission($name, $role)
    {
        $permission = Permission::create(['name' => $name]);
        $role->permissions()->attach($permission);
    }
}
