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
        Schema::create('adisyon_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('adisyon_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
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
                ->foreign('service_id')
                ->references('id')
                ->on('services')
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
        Schema::dropIfExists('adisyon_services');
    }
};
