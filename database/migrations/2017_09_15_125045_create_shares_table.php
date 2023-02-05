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
        Schema::create('shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('portfolio_id')->unsigned();
            $table->integer('symbol_id')->unsigned();
            $table->string('lot')->default('0');
            $table->string('average')->unsigned()->default('0');
            $table->string('average_amount')->unsigned()->default('0');
            $table->string('amount')->unsigned()->default('0');
            $table->string('gain')->default('0');
            $table->string('total_sale_amount')->unsigned()->default('0');
            $table->string('total_purchase_amount')->default('0');
            $table->string('paid_amount')->default('0');
            $table->string('gain_loss')->default('0');
            $table->string('total_commission_amount')->unsigned()->default('0');
            $table->string('total_dividend_gain')->unsigned()->default('0');
            $table->string('total_bonus_share')->default('0.000');
            $table->string('total_rights_share')->default('0.000');
            $table->string('total_gain')->default('0');
            $table->string('instant_gain')->default('0');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('portfolio_id')
                  ->references('id')->on('portfolios')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('symbol_id')
                  ->references('id')->on('symbols')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unique(['portfolio_id', 'symbol_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shares');
    }
};
