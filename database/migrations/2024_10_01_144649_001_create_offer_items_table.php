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
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('offer_id')
                ->unsigned()
                ->index();
            $table->string('itemable_type', 255)->index();
            $table
                ->bigInteger('itemable_id')
                ->unsigned()
                ->index();
            $table->integer('quantity')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index(['itemable_type', 'itemable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_items');
    }
};
