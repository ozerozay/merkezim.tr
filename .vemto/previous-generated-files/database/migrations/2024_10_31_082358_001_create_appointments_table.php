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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('service_room_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('service_category_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->json('service_ids')->nullable();
            $table->date('date');
            $table->integer('duration');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->string('status', 255);
            $table->string('message', 255);
            $table->json('finish_service_ids')->nullable();
            $table
                ->bigInteger('forward_user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('finish_user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->json('reservation_service_ids')->nullable();
            $table->string('type', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('client_id')
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
            $table
                ->foreign('service_room_id')
                ->references('id')
                ->on('service_rooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('service_category_id')
                ->references('id')
                ->on('service_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('forward_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('finish_user_id')
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
        Schema::dropIfExists('appointments');
    }
};
