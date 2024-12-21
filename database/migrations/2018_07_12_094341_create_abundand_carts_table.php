<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbundandCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abundand_carts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('data');
            $table->string('cart_update');
            $table->string('cart_user');
            $table->string('cart_products');
            $table->string('cart_sync');
            $table->string('admin_mail_send');
            $table->string('method');
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
        Schema::dropIfExists('abundand_carts');
    }
}
