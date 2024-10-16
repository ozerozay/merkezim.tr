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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 9)->unique();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->index();
            $table->date('expire_date')->nullable();
            $table->decimal('price');
            $table->enum('status', ['waiting', 'success', 'cancel']);
            $table->string('message', 255);
            $table->tinyInteger('month');
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
                ->foreign('client_id')
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
        Schema::dropIfExists('offers');
    }
};
