<?php

namespace Database\Seeders;

use App\Models\ModulePermission;
use Illuminate\Database\Seeder;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moduleList = [
            [ 
                'parent' => '0', 
                'module_name' => 'Users', 
                'route_url' => 'admin.users', 
                'icon' => 'fas fa-user-alt'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'User Roles', 
                'route_url' => 'admin.user-roles', 
                'icon' => 'mdi mdi-account-group',
                'is_active' => 'active'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'Products', 
                'route_url' => 'admin.products', 
                'icon' => 'fe-shopping-bag',
                'is_active' => 'active'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'Categorys', 
                'route_url' => 'admin.category', 
                'icon' => 'fas fa-list',
                'is_active' => 'active'
            ],
        ];        

        foreach ($moduleList as $module) {
            if(!$moduleInfo = ModulePermission::where(['module_name' => $module['module_name']])->first())
            {
                ModulePermission::create($module);      
            }
            else
            {
                $moduleInfo->update($module);
            }
            
        }
    }
}
