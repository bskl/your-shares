<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioSymbolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_symbol', function (Blueprint $table) {
            $table->integer('portfolio_id')->unsigned();;
            $table->integer('symbol_id')->unsigned();;

            $table->foreign('portfolio_id')
                  ->references('id')->on('portfolios')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('symbol_id')
                  ->references('id')->on('symbols')
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
        Schema::dropIfExists('portfolio_symbol');
    }
}
