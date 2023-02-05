<?php

use App\Enums\Currency;
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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('currency', 3)->default(Currency::Try->value);
            $table->string('commission')->default('0.00000');
            $table->integer('order');
            $table->boolean('filtered');
            $table->string('total_sale_amount')->default('0');
            $table->string('total_purchase_amount')->default('0');
            $table->string('paid_amount')->default('0');
            $table->string('gain_loss')->default('0');
            $table->string('total_commission_amount')->default('0');
            $table->string('total_dividend_gain')->default('0');
            $table->string('total_bonus_share')->default('0');
            $table->string('total_rights_share')->default('0');
            $table->string('total_gain')->default('0');
            $table->string('instant_gain')->default('0');
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
};
