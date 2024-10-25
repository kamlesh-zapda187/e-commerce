<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use HasFactory;

    protected $fillable = ['parent', 'module_name', 'route_url','icon','is_active'];

    /*public function roles(){
        return $this->belongsToMany(RolePermission::class,'role_permission');
    }*/

   

    public function roles()
    {
        return $this->belongsToMany(UserRole::class, 'role_permission', 'module_id', 'role_id');
    }
    
   public function route_permission(){
        return $this->hasMany(RolePermission::class, 'module_id', 'id');
   }

   

}
