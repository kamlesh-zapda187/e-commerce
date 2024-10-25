<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast;

// Authenticatable
class User extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $fillable = [
    		"role_id",
    		"first_name",
    		"last_name",
    		"full_name",
    		"email",
    		"username",
    		"password",
    		"contact",
    		"dob",
            "gender",
    		"address",
    		"address_line_2",
    		"country_name",
    		"country_code",
    		"state",
    		"city",
    		"zip_code",
    		"user_avatar",
    		"token",
    		"is_active",
    		"is_verify",
    		"is_agree",
    		"created_by",
    		"last_login_time",
    		"is_deleted",
    ];

    public function role(){
        //return $this->hasOne(UserRole::class,'id','role_id');
        return $this->belongsTo(UserRole::class, 'role_id');
    }

      public function hasPermission($route_name,$action='can_view')
      {

        if($this->role_id == 1 && $this->role->role_constant == 'ADMIN'){
            return true;
        }

        $userRoleAccess = ModulePermission::join('role_permissions', 'module_permissions.id', '=', 'role_permissions.module_id')
                                            ->select('module_permissions.module_name','module_permissions.route_url', 'role_permissions.*')
                                            ->where(['module_permissions.route_url' => $route_name, 'role_id' => $this->role_id]) // Select columns you need
                                            ->first();
        if(!empty($userRoleAccess))
        {
            if($userRoleAccess[$action] && !empty($userRoleAccess[$action]))
            {
                return true;
            }
        }
        
        return false;

    }

}
