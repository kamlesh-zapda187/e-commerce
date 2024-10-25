<?php

use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->index();
            $table->string('first_name')->index();
            $table->string('last_name')->nullable()->index();
            $table->string('full_name')->nullable()->index();
            $table->string('email')->unique()->index();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('contact')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('user_avatar')->nullable();
            $table->string('token')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('user account is active or not ( 1=active, 0=inactiive ) ');
            $table->tinyInteger('is_verify')->default(1)->comment('user account is verified or not ( 1=Yes, 0=No ) ');
            $table->tinyInteger('is_agree')->default(1)->comment('user is agree team and conditions ( 1=Yes, 0=No )');
            $table->integer('created_by')->nullable();
            $table->dateTime('last_login_time')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment('user is deleted or not ( 1=Yes, 0=No )');

            $table->timestamps();

            /*
            $table->foreign('role_id')
                ->references('id')
                ->on((new UserRole())->getTable())
                ->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
