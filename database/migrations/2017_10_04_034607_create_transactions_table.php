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
            $table->timestamp('date_at')->useCurrent();
            $table->decimal('lot', 11, 3)->unsigned();
            $table->integer('remaining')->unsigned()->nullable()->default(0);
            $table->integer('price')->unsigned()->default(0);
            $table->integer('amount')->unsigned()->nullable()->default(0);
            $table->decimal('commission', 5, 5)->unsigned()->nullable()->default(0);
            $table->integer('commission_price')->unsigned()->nullable()->default(0);
            $table->integer('sale_average')->nullable()->default(0);
            $table->integer('sale_average_amount')->nullable()->default(0);
            $table->integer('sale_gain')->nullable()->default(0);
            $table->integer('dividend')->unsigned()->nullable()->default(0);
            $table->integer('dividend_gain')->unsigned()->nullable()->default(0);
            $table->float('bonus', 7, 4)->unsigned()->nullable()->default(0);
            $table->float('rights', 7, 4)->unsigned()->nullable()->default(0);
            $table->decimal('exchange_ratio', 17, 15)->unsigned()->nullable()->default(0);
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
}
