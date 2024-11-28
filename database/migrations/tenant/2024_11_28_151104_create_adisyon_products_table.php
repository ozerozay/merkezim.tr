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
        Schema::create('adisyon_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('adisyon_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('total')->unsigned();
            $table->boolean('gift');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('adisyon_id')
                ->references('id')
                ->on('adisyons')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adisyon_products');
    }
};
