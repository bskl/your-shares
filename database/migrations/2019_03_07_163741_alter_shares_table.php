<?php

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
        Schema::table('shares', function (Blueprint $table) {
            $table->string('average_with_dividend')->default('0')->after('average');
            $table->string('average_amount_with_dividend')->default('0')->after('average_amount');
            $table->string('gain_with_dividend')->default('0')->after('gain');
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
            $table->dropColumn('average_with_dividend');
            $table->dropColumn('average_amount_with_dividend');
            $table->dropColumn('gain_with_dividend');
        });
    }
};
