<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();
            $table->enum('user_type',['register_user','guest_user '])->default('guest_user')->index();
            $table->string('order_no');
            $table->integer('buyer_id')->nullable()->index();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('shipping_charge', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('shipping_first_name')->index();
            $table->string('shipping_last_name')->index();
            $table->string('shipping_name')->index();
            $table->string('shipping_email')->index();
            $table->string('shipping_contact');
            $table->string('shipping_address');
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_zipcode');
            $table->string('shipping_country')->nullable();            
            $table->string('billing_country')->nullable();
            $table->string('additional_information')->nullable();
            $table->string('rejection_note')->nullable();
            $table->enum('order_status',['pending','confirm','shipped','delivered','rejected'])->default('pending')->index();
            $table->enum('payment_method',['COD','CARD'])->default('CARD');
            $table->string('payment_intent_id');
            $table->enum('payment_status',['pending','success','failed']);
            $table->dateTime('payment_date')->nullable();
            $table->string('promo_code')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
