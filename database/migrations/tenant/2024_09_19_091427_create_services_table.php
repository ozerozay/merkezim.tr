<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('category_id')
                ->unsigned()
                ->index();
            $table->string('name', 255);
            $table->tinyInteger('gender');
            $table->tinyInteger('seans');
            $table->integer('duration');
            $table->decimal('price');
            $table->integer('min_day');
            $table->boolean('active')->default(true);
            $table->boolean('visible')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('category_id')
                ->references('id')
                ->on('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
