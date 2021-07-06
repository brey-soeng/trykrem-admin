<?php

namespace Database\Seeders;

use App\Models\SysAdmin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $system = Permission::create(['pid' => 0, 'name' => 'system', 'title' => 'System', 'icon' => 'el-icon-s-tools', 'path' => '/system', 'component' => 'layout/Layout', 'guard_name' => 'admin', 'hidden' => 0]);

        $permission = Permission::create(['pid' => $system->id, 'name' => 'permission', 'title' => 'permission', 'icon' => 'lock', 'path' => '/permission', 'component' => 'admin/permission/index', 'guard_name' => 'admin', 'hidden' => 0]);
        Permission::create(['pid' => $permission->id, 'name' => 'permission.create', 'title' => 'add', 'icon' => 'icon', 'path' => 'permission/create', 'component' => 'admin/permission/create', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $permission->id, 'name' => 'permission.update', 'title' => 'Edit', 'icon' => 'icon', 'path' => 'permission/update', 'component' => 'admin/permission/update', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $permission->id, 'name' => 'permission.delete', 'title' => 'delete', 'icon' => 'icon', 'path' => 'permission/delete', 'component' => 'admin/permission/delete', 'guard_name' => 'admin', 'hidden' => 1]);

        $administrator = Permission::create(['pid' => $system->id, 'name' => 'administrator', 'title' => 'administrator', 'icon' => 'lock', 'path' => '/administrator', 'component' => 'admin/administrator/index', 'guard_name' => 'admin', 'hidden' => 0]);
        Permission::create(['pid' => $administrator->id, 'name' => 'administrator.create', 'title' => 'add', 'icon' => 'icon', 'path' => 'administrator/create', 'component' => 'admin/administrator/create', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $administrator->id, 'name' => 'administrator.update', 'title' => 'edit', 'icon' => 'icon', 'path' => 'administrator/update', 'component' => 'admin/administrator/update', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $administrator->id, 'name' => 'administrator.delete', 'title' => 'delete', 'icon' => 'icon', 'path' => 'administrator/delete', 'component' => 'admin/administrator/delete', 'guard_name' => 'admin', 'hidden' => 1]);

        $roles = Permission::create(['pid' => $system->id, 'name' => 'roles', 'title' => 'roles', 'icon' => 'lock', 'path' => '/roles', 'component' => 'admin/roles/index', 'guard_name' => 'admin', 'hidden' => 0]);
        Permission::create(['pid' => $roles->id, 'name' => 'roles.create', 'title' => 'add', 'icon' => 'icon', 'path' => 'roles/create', 'component' => 'admin/roles/create', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $roles->id, 'name' => 'roles.update', 'title' => 'edit', 'icon' => 'icon', 'path' => 'roles/update', 'component' => 'admin/roles/update', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $roles->id, 'name' => 'roles.delete', 'title' => 'delete', 'icon' => 'icon', 'path' => 'roles/delete', 'component' => 'admin/roles/delete', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $roles->id, 'name' => 'role.role', 'title' => 'Role details', 'icon' => 'icon', 'path' => 'role/role', 'component' => 'admin/roles/role', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $roles->id, 'name' => 'role.syncPermissions', 'title' => 'Assign permissions/directories', 'icon' => 'icon', 'path' => 'roles/syncPermissions', 'component' => 'admin/roles/syncPermissions', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $roles->id, 'name' => 'role.syncRoles', 'title' => 'Assign users', 'icon' => 'icon', 'path' => 'role/syncRoles', 'component' => 'admin/roles/syncRoles', 'guard_name' => 'admin', 'hidden' => 1]);


        $authentication = Permission::create(['pid' => 0, 'name' => 'authentication', 'title' => 'Authentication', 'icon' => 'el-icon-s-tools', 'path' => '/authentication', 'component' => 'layout/Layout', 'guard_name' => 'admin', 'hidden' => 0]);
        $auth = Permission::create(['pid' => $authentication->id, 'name' => 'reset.password', 'title' => 'Reset Password', 'icon' => 'el-icon-key', 'path' => '/reset-password', 'component' => 'admin/reset-password/index', 'guard_name' => 'admin', 'hidden' => 0]);
        Permission::create(['pid' => $auth->id, 'name' => 'reset.create', 'title' => 'Add password', 'icon' => 'icon', 'path' => 'reset/create', 'component' => 'admin/reset-password/create', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $auth->id, 'name' => 'reset.update', 'title' => 'Edit password', 'icon' => 'icon', 'path' => 'reset/update', 'component' => 'admin/reset-password/update', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' => $auth->id, 'name' => 'reset.delete', 'title' => 'Remove password', 'icon' => 'icon', 'path' => 'reset/delete', 'component' => 'admin/reset-password/delete', 'guard_name' => 'admin', 'hidden' => 1]);
        Permission::create(['pid' =>$auth->id,  'name' => 'reset.detail', 'title' => 'reset details', 'icon' => 'icon', 'path' => 'reset/details', 'component' => 'admin/reset-password/detail', 'guard_name' => 'admin', 'hidden' => 1]);



        $role1 = Role::create(['name' => 'Administrator', 'guard_name' => 'admin']);
        $role1->givePermissionTo([
            'system',
            // permission
            'permission',
            'permission.create',
            'permission.update',
            'permission.delete',
            //administrator
            'administrator',
            'administrator.create',
            'administrator.update',
            'administrator.delete',
            //roles
            'roles',
            'roles.create',
            'roles.update',
            'roles.delete',
            'role.syncPermissions',
            'role.syncRoles',

            'authentication',
            'reset.password',
            'reset.create' ,
            'reset.update',
            'reset.delete',
            'reset.detail'

        ]);
        $user = SysAdmin::find(1);
        $user->assignRole('Administrator');
        $role2 = Role::create(['name' => 'Editors', 'guard_name' => 'admin']);
        $role2->givePermissionTo([
            'authentication',
            'reset.password',
            'reset.create' ,
            'reset.update',
            'reset.delete',
            'reset.detail'
        ]);
        $user = SysAdmin::find(2);
        $user->assignRole('Editors');
    }

}
