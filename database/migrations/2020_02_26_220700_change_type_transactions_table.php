<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('lot')->change();
            $table->string('amount')->change();
            $table->string('sale_average_amount')->change();
            $table->string('sale_gain')->change();
            $table->string('dividend_gain')->change();
            $table->string('bonus')->change();
            $table->string('rights')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('lot');
            $table->dropColumn('amount');
            $table->dropColumn('sale_average_amount');
            $table->dropColumn('sale_gain');
            $table->dropColumn('dividend_gain');
            $table->dropColumn('bonus');
            $table->dropColumn('rights');
        });
    }
}
