<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setUpRolesAndPermissions();
    }

    private function setUpRolesAndPermissions(): void
    {

        $roles_structure = Config::get('permission.roles_structure');
        $permissions_map = collect(config('permission.permissions_map'));
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        foreach($roles_structure as $role_name => $role_value){
            $role = Role::firstOrCreate(['name' => $role_name,'display_name'=>$role_name]);
            foreach($role_value as $module => $permission_content){
                foreach (explode(',', $permission_content) as $p => $perm) {
                    $permission = Permission::firstOrCreate(['name' => $module.'-'.$permissions_map[$perm],'table'=>$module]);
                    $role->givePermissionTo($permission);
                }
            }

        }
    }
}
