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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->json('branch_ids');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->rawIndex('(cast(`branch_ids` as unsigned array))', 'category_branch_id_index');
            $table->double('price');
            $table->tinyInteger('earn')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
