<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
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
            $table->integer('portfolio_symbol_id')->unsigned();
            $table->integer('type');
            $table->timestamp('date');
            $table->integer('share');
            $table->integer('price');
            $table->decimal('commission', 1,4);
            $table->timestamps();

            $table->foreign('portfolio_symbol_id')
                ->references('id')->on('portfolio_symbols')
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
}
