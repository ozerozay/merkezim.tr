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
        Schema::create('service_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->index('branch_service_room_branch_id');
            $table->json('category_ids');
            $table->rawIndex('(cast(`category_ids` as unsigned array))', 'service_rooms_category_ids_index');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_rooms');
    }
};
