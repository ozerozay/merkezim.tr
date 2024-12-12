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
        Schema::create('shop_services', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('service_id')
                ->unsigned()
                ->index();
            $table->string('unique_id');
            $table->boolean('active')->default(true);
            $table->string('discount_text')->nullable();
            $table->decimal('price');
            $table->text('description');
            $table->string('name');
            $table->integer('buy_max')->nullable();
            $table->integer('buy_min')->nullable();
            $table->integer('kdv')->nullable();
            $table->timestamps();

            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches');
            $table
                ->foreign('service_id')
                ->references('id')
                ->on('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_services');
    }
};
