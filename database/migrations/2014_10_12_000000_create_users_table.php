<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('locale', 2)->nullable();
            $table->string('role')->default(App\Enums\User::USER);
            $table->timestamp('logon_at')->nullable();
            $table->string('logon_host', 50)->nullable();
            $table->rememberToken();
            $table->boolean('confirmed')->default(App\Enums\User::WAITING);
            $table->string('confirmation_code', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
