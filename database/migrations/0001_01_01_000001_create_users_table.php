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
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->json('staff_branches')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('unique_id', 9)->unique();
            $table->string('referans_id', 9)->nullable();
            $table->string('country', 255);
            $table->string('phone', 255)->unique();
            $table->string('phone_code', 4)->nullable();
            $table->string('tckimlik', 11)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('adres', 255)->nullable();
            $table->tinyInteger('il')->nullable();
            $table->tinyInteger('ilce')->nullable();
            $table
                ->string('email', 255)
                ->nullable()
                ->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->boolean('gender')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('first_login');
            $table->string('remember_token', 100)->nullable();
            $table->json('labels')->nullable();
            $table->json('instant_approves')->nullable();
            $table
                ->boolean('instant_approve')
                ->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table
                ->foreignId('user_id')
                ->nullable()
                ->index();
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
