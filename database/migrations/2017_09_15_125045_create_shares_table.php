<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharesTable extends Migration
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
            $table->decimal('lot', 11, 3)->default(0);
            $table->integer('average')->default(0);
            $table->integer('average_amount')->default(0);
            $table->integer('amount')->default(0);
            $table->integer('gain')->default(0);
            $table->integer('total_sale_amount')->default(0);
            $table->integer('total_purchase_amount')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('gain_loss')->default(0);
            $table->integer('total_commission_amount')->default(0);
            $table->integer('total_dividend_gain')->default(0);
            $table->float('total_bonus_share', 8, 3)->default(0);
            $table->integer('total_gain')->default(0);
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
}
