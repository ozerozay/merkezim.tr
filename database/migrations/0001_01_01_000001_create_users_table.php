<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->index('user_branch_id_index');
            $table->json('staff_branches')->nullable();
            $table->string('name')->nullable();
            $table->string('unique_id', 9)->unique();
            $table->string('referans_id', 9)->nullable();
            $table->string('country');
            $table->string('phone')->unique();
            $table->string('phone_code', 4)->nullable();
            $table->string('tckimlik', 11)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('adres')->nullable();
            $table->tinyinteger('il')->nullable();
            $table->tinyInteger('ilce')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('gender')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('first_login')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
