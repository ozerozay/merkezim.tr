<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('service_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('sale_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('offer_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('package_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('taksit_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->integer('total');
            $table->integer('remaining');
            $table->boolean('gift');
            $table->text('message')->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->enum('status', [
                'success',
                'waiting',
                'cancel',
                'freeze',
                'expired',
            ]);
            $table->date('expire_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

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
                ->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('taksit_id')
                ->references('id')
                ->on('client_taksits')
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
