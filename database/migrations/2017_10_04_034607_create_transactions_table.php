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
            $table->integer('share_id')->unsigned();
            $table->integer('type');
            $table->timestamp('date');
            $table->integer('lot');
            $table->integer('price');
            $table->integer('amount')->default(0);
            $table->decimal('commission', 5, 4);
            $table->integer('commission_price')->default(0);
            $table->integer('average')->default(0);
            $table->integer('sale_gain')->default(0);
            $table->timestamps();

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
}
