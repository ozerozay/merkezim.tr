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
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->bigInteger('client_id')->unsigned();
            $table
                ->bigInteger('service_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->integer('total');
            $table->integer('remaining');
            $table
                ->bigInteger('sale_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->boolean('gift');
            $table->text('message')->nullable();
            $table
                ->bigInteger('package_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('sale_id')
                ->references('id')
                ->on('sale')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('package_id')
                ->references('id')
                ->on('packages')
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
        Schema::dropIfExists('client_services');
    }
};
