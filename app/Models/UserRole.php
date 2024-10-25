<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    
    public function users()
    {
        return $this->hasMany(User::class, 'role_id'); // Foreign key is role_id in users table
    }
    

    public function permissions()
    {
        return $this->belongsToMany(ModulePermission::class, 'role_permissions', 'role_id', 'module_id');
        //return $this->belongsToMany(ModulePermission::class, 'role_permissions', 'role_id', 'module_id');
    }

    /*public function users()
    {
        return $this->hasMany(User::class, 'role_id'); // Assuming role_id is the foreign key in users table
    }

    public function permissions()
    {
        return $this->belongsToMany(ModulePermission::class, 'role_permissions', 'role_id', 'module_id');
    }
    */

    /*
    public function permissions(){
        return $this->belongsToMany(ModulePermission::class,'role_permission');
    }
    */

    public static function saveRoleAccess($request)
    {
        $role_id   = $request->input('role_id');
    	$moduleIds = $request->input('module');

        foreach ($moduleIds as $moduleId) {
            if(!$RolePermission = RolePermission::where(["role_id" => $role_id,'module_id' => $moduleId])->first())
                $RolePermission = new RolePermission;

            $RolePermission->role_id    = $role_id;
            $RolePermission->module_id  = $moduleId;
            $RolePermission->can_view   = ($request->input('view_' . $moduleId)) ? "1" : "0";
            $RolePermission->can_insert = ($request->input('insert_' . $moduleId)) ? "1" : "0";
            $RolePermission->can_update = ($request->input('update_' . $moduleId)) ? "1" : "0";
            $RolePermission->can_delete = ($request->input('delete_' . $moduleId)) ? "1" : "0";
            $RolePermission->save();
    	}

    	return TRUE;
    }
}
