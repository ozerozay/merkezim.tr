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
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table
                ->bigInteger('sale_id')
                ->unsigned()
                ->nullable();
            $table->decimal('total');
            $table->decimal('remaining');
            $table->date('date');
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
