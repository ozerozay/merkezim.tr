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
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('type');
            $table->text('message');
            $table->json('data')->nullable();
            $table->text('user_message')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('status')->default(\App\Enum\ClientRequestStatus::pending->name);
            $table->softDeletes();
            $table->timestamps();

            $table
                ->foreign('client_id')
                ->references('id')
                ->on('users');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_requests');
    }
};
