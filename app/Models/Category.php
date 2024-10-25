<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function get_categories(){
        return SELF::where(['is_active'=>1])->where('is_deleted','!=',1)->pluck('name','id')->toArray();
    }

    public function get_categoryId_byName($category_name){
        if($category = SELF::select('id')->where(['is_active'=>1,'name'=>$category_name])->where('is_deleted','!=',1)->first()){
            return $category->id;
        }
        return null;
    }
}
