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
        Schema::create('shop_packages', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('package_id')
                ->unsigned()
                ->index();
            $table->string('unique_id');
            $table->string('name');
            $table->text('description');
            $table->boolean('active')->default(true);
            $table->string('discount_text')->nullable();
            $table->decimal('price');
            $table->integer('buy_max')->nullable();
            $table->integer('month')->nullable();
            $table->integer('kdv')->nullable();
            $table->timestamps();

            $table
                ->foreign('package_id')
                ->references('id')
                ->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_packages');
    }
};
