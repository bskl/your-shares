<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('share_id')->unsigned();
            $table->integer('type');
            $table->timestamp('date_at')->useCurrent();
            $table->string('lot');
            $table->string('remaining')->nullable()->default('0');
            $table->string('price')->unsigned()->nullable()->default('0');
            $table->string('amount')->unsigned()->nullable()->default('0');
            $table->string('commission')->unsigned()->nullable()->default('0.00000');
            $table->string('commission_price')->unsigned()->nullable()->default('0');
            $table->string('sale_average')->nullable()->default('0');
            $table->string('sale_average_amount')->nullable()->default('0');
            $table->string('sale_gain')->nullable()->default('0');
            $table->string('dividend')->unsigned()->nullable()->default('0');
            $table->string('dividend_gain')->unsigned()->nullable()->default('0');
            $table->string('bonus')->unsigned()->nullable()->default('0.0000');
            $table->string('rights')->unsigned()->nullable()->default('0.0000');
            $table->string('preference')->unsigned()->nullable()->default('0.000');
            $table->string('exchange_ratio')->unsigned()->nullable()->default('0.000000000000000');
            $table->string('symbol_code', 10)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('share_id')
                  ->references('id')->on('shares')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
