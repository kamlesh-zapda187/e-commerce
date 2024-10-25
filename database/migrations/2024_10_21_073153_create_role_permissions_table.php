<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->comment('Foreign key reference user_roles.id');
            $table->unsignedBigInteger('module_id')->comment('Foreign key reference module_permissions.id');
            $table->tinyInteger('can_view')->default(0)->nullable()->comment('0=denied 1=access');
            $table->tinyInteger('can_insert')->default(0)->nullable()->comment('0=denied 1=access');
            $table->tinyInteger('can_update')->default(0)->nullable()->comment('0=denied 1=access');
            $table->tinyInteger('can_delete')->default(0)->nullable()->comment('0=denied 1=access');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('module_permissions')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
