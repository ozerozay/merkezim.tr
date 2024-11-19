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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->json('branch_ids');
            $table->tinyInteger('gender')->default(0);
            $table->string('name');
            $table->double('price');
            $table->boolean('active')->default(true);
            $table->tinyInteger('buy_time');
            $table->rawIndex('(cast(`branch_ids` as unsigned array))', 'package_branch_id_index');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};