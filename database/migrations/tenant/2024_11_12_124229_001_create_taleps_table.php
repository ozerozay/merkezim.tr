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
        Schema::create('taleps', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('phone', 10);
            $table->string('phone_long', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->date('date');
            $table->string('status', 255)->default('bekleniyor');
            $table->string('message', 255);
            $table->string('type', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches')
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
        Schema::dropIfExists('taleps');
    }
};
