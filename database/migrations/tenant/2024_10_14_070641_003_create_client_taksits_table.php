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
        Schema::create('client_taksits', function (Blueprint $table) {
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
                ->bigInteger('sale_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->decimal('total');
            $table->decimal('remaining');
            $table->date('date');
            $table->enum('status', [
                'waiting',
                'success',
                'cancel',
                'freeze',
                'expired',
            ]);
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
                ->foreign('sale_id')
                ->references('id')
                ->on('sale')
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
        Schema::dropIfExists('client_taksits');
    }
};
