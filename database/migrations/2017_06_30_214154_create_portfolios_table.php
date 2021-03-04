<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->decimal('commission', 5, 5)->default(0);
            $table->integer('order');
            $table->integer('total_sale_amount')->unsigned()->default(0);
            $table->integer('total_purchase_amount')->unsigned()->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('gain_loss')->default(0);
            $table->integer('total_commission_amount')->unsigned()->default(0);
            $table->integer('total_dividend_gain')->unsigned()->default(0);
            $table->float('total_bonus_share', 11, 3)->default(0);
            $table->float('total_rights_share', 11, 3)->default(0);
            $table->integer('total_gain')->default(0);
            $table->integer('instant_gain')->default(0);
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
