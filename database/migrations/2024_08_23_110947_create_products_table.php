<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category_id')->index();
            $table->string('product_title')->index();
            $table->double('price')->index();
            $table->string('product_image')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('Product is active or not ( 1=active, 0=inactiive ) ');
            $table->tinyInteger('is_deleted')->default(0)->comment('Product is deleted or not ( 1=deleted, 0=not ) ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
