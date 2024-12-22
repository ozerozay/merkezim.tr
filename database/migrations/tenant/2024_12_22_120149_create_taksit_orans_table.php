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
        Schema::create('taksit_orans', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->json('data')->nullable();
            $table->timestamps();

            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taksit_orans');
    }
};
