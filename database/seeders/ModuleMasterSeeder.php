<?php

namespace Database\Seeders;

use App\Models\ModulePermission;
use Illuminate\Database\Seeder;

class ModuleMasterSeeder extends Seeder
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
                'url' => 'admin/users', 
                'icon' => 'fas fa-user-alt'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'User Roles', 
                'url' => 'admin/user-roles', 
                'icon' => 'mdi mdi-account-group',
                'is_active' => 'active'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'Products', 
                'url' => 'admin/products', 
                'icon' => 'fe-shopping-bag',
                'is_active' => 'active'
            ],

            [ 
                'parent' => '0', 
                'module_name' => 'Categorys', 
                'url' => 'admin/category', 
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
