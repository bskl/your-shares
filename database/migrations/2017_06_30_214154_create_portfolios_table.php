<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('currency', 3)->default(App\Enums\SupportedCurrencies::DEFAULT);
            $table->integer('order');
            $table->integer('total_sale_amount')->default(0);
            $table->integer('total_average_amount')->default(0);
            $table->integer('total_commission_amount')->default(0);
            $table->integer('total_dividend_gain')->default(0);
            $table->float('total_bonus_issue_share', 8, 3)->default(0);
            $table->integer('total_gain')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('portfolios');
    }
}
