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
        Schema::create('mahsups', function (Blueprint $table) {
            $table
                ->bigInteger('giris_kasa_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('cikis_kasa_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('transaction_giris_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('transaction_cikis_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->date('date');
            $table->decimal('price');
            $table->string('message', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->id();

            $table
                ->foreign('giris_kasa_id')
                ->references('id')
                ->on('kasas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('cikis_kasa_id')
                ->references('id')
                ->on('kasas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('transaction_giris_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('transaction_cikis_id')
                ->references('id')
                ->on('transactions')
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
        Schema::dropIfExists('mahsups');
    }
};
