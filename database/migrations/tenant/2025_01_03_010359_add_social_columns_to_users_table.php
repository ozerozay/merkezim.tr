<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->nullable(); // Sosyal platform ID'si
            $table->string('provider')->nullable(); // Platform adı (Google, Facebook, vb.)
            $table->string('avatar')->nullable(); // Kullanıcının avatar URL'si
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider_id', 'provider', 'avatar']);
        });
    }
}
