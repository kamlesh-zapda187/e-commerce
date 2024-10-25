<?php

namespace App\Http\Controllers;

use App\Models\CustomeMigration;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomeMiration extends Controller
{
    public function __construct()
    {
        $defaultMethodList = [
            'index',
            '__construct',
            'middleware',
            'getMiddleware',
            'callAction',
            '__call',
            'authorize',
            'authorizeForUser',
            'parseAbilityAndArguments',
            'normalizeGuessedAbilityName',
            'authorizeResource',
            'resourceAbilityMap',
            'resourceMethodsWithoutModels',
            'dispatch',
            'dispatchNow',
            'dispatchSync',
            'validateWith',
            'validate',
            'validateWithBag',
            'getValidationFactory',
        ];
        $migrationArray = get_class_methods($this);

        $migrationArray = array_diff($migrationArray, $defaultMethodList);


        $result = $users = DB::table('custome_migrations')->get();
        //dd(count($result));
        $existMigrationArray = [];

        if (count($result) > 0)
        {
            foreach ($result as $row)
            {
                $existMigrationArray[] = $row->migration_name;
            }
        }

        

        //remove exists migration
        $newMigrationArray = array();
        foreach ($migrationArray as $migration)
        {
            if (!in_array($migration, $existMigrationArray))
            {
                $newMigrationArray[] = $migration;
            }
        }


        
        echo "===========================<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".count($newMigrationArray)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New Migrations<br>";
        echo "===========================<br><br><br><br>";

        //call migration
        foreach ($newMigrationArray as $runMigration)
        {
           $this->$runMigration();

            $customeMigration = new CustomeMigration();    
            $customeMigration->migration_name = $runMigration;
            $customeMigration->save();

           

            //$migrationStatus = $this->db->insert('migration',array('migration_name'=>$runMigration, 'created_at'=>time(), 'update_at'=>time()));
        }

       // dd($migrationArray);
    }

    public function index()
    {
        echo "<br><br><br><br>Migration Run Successfully";
    }

    public function insert_admin_role_in_userRole_tables(){
    	//execute query

        $userRole = new UserRole();
        $userRole->name          = 'Admin';
        $userRole->role_constant = 'ADMIN';
        $userRole->is_static     = '0';
        $userRole->is_active     = '1';

        $userRole->save();

        $userRole = new UserRole();
        $userRole->name          = 'Customer';
        $userRole->role_constant = 'CUSTOMER';
        $userRole->is_static     = '0';
        $userRole->is_active     = '1';

        $userRole->save();

    	echo "============================<br>";
    	echo "successfully insert user role in  role  table<br>";
    	echo "============================<br><br><br>";
    }

    public function insert_admin_user_in_users_tables(){
    	//execute query

        $user = new User();
        $user->role_id          = '1';
        $user->first_name = 'Super';
        $user->last_name = 'Admin';
        $user->full_name = 'Supper Admin';
        $user->email = 'admin@gmail.com';
        $user->username = 'admin';
        $user->password = Hash::make('admin');
        $user->is_active     = '1';
        $user->save();

    	echo "============================<br>";
    	echo "successfully insert user  in  user  table<br>";
    	echo "============================<br><br><br>";
    }
}
