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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->index('sms_user_id_index');
            $table->foreignId('type_id')->nullable()->constrained('sms_action_templates')->index('sms_action_id_index');
            $table->string('country');
            $table->string('phone');
            $table->text('message');
            $table->string('type_id')->nullable();
            $table->string('campaign_id')->nullable();
            $table->json('status')->nullable();
            $table->rawIndex('(cast(`status` as unsigned array))', 'sms_status_index');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};