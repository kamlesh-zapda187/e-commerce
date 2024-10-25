<?php

use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_constant');
            $table->tinyInteger('is_static')->default('0')->comment('0 = static, 1=dynamic');
            $table->tinyInteger('is_active')->default('1')->comment('1=active, 0=inactive');
            $table->timestamps();

           /*
            $userRole = new UserRole();
            $userRole->name = 'Admin';
            $userRole->role_constant = '0';
            $userRole->is_static = 'Admin';
            $userRole->is_active = '1';
            $userRole->save();
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
