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
        Schema::create('talep_processes', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('talep_id')
                ->unsigned()
                ->index();
            $table->string('status', 255);
            $table->string('message', 255);
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->dateTime('date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('talep_id')
                ->references('id')
                ->on('taleps')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('talep_processes');
    }
};
