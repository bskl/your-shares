<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('user_id')->unsigned();
            $table->integer('share_id')->unsigned();
            $table->integer('type');
            $table->timestamp('date_at');
            $table->decimal('lot', 11, 3);
            $table->integer('remaining')->nullable()->default(0);
            $table->integer('price');
            $table->integer('amount')->nullable()->default(0);
            $table->decimal('commission', 5, 5)->nullable()->default(0);
            $table->integer('commission_price')->nullable()->default(0);
            $table->integer('sale_average')->nullable()->default(0);
            $table->integer('sale_average_amount')->nullable()->default(0);
            $table->integer('sale_gain')->nullable()->default(0);
            $table->integer('dividend')->nullable()->default(0);
            $table->integer('dividend_gain')->nullable()->default(0);
            $table->float('bonus_issue', 5, 4)->nullable()->default(0);
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
}
