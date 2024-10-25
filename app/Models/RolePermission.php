<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',        // Add role_id here
        'module_id','can_view','can_insert','can_update','can_delete'
    ];

    public function role(){
        return $this->belongsTo(UserRole::class);
    }

    public function route_permission(){
        return $this->hasMany(ModulePermission::class, 'id', 'module_id');
   }

   

    
}
