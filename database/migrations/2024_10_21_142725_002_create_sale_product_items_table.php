<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_items', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('sale_product_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('product_id')
                ->unsigned()
                ->index();
            $table->integer('quantity')->unsigned();
            $table->decimal('price');
            $table->boolean('gift')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('sale_product_id')
                ->references('id')
                ->on('sale_product')
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_product_items');
    }
};
