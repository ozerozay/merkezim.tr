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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kasa_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->date('date');
            $table->decimal('price');
            $table->text('message')->nullable();
            $table->string('type');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('kasa_id')
                ->references('id')
                ->on('kasas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('transactions');
    }
};
