<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->string('lot')->change();
            $table->string('average')->change();
            $table->string('average_with_dividend')->change();
            $table->string('average_amount')->change();
            $table->string('average_amount_with_dividend')->change();
            $table->string('amount')->change();
            $table->string('gain')->change();
            $table->string('gain_with_dividend')->change();
            $table->string('total_sale_amount')->change();
            $table->string('total_purchase_amount')->change();
            $table->string('paid_amount')->change();
            $table->string('gain_loss')->change();
            $table->string('total_dividend_gain')->change();
            $table->string('total_bonus_share')->change();
            $table->string('total_rights_share')->change();
            $table->string('total_gain')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->dropColumn('lot');
            $table->dropColumn('average');
            $table->dropColumn('average_with_dividend');
            $table->dropColumn('average_amount');
            $table->dropColumn('average_amount_with_dividend');
            $table->dropColumn('amount');
            $table->dropColumn('gain');
            $table->dropColumn('gain_with_dividend');
            $table->dropColumn('total_sale_amount');
            $table->dropColumn('total_purchase_amount');
            $table->dropColumn('paid_amount');
            $table->dropColumn('gain_loss');
            $table->dropColumn('total_dividend_gain');
            $table->dropColumn('total_bonus_share');
            $table->dropColumn('total_rights_share');
            $table->dropColumn('total_gain');
        });
    }
}
