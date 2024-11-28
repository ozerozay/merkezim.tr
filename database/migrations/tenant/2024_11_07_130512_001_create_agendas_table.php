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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('talep_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('name', 255);
            $table->text('message');
            $table->date('date');
            $table->date('date_create');
            $table->time('time')->nullable();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->string('status', 255)->default('waiting');
            $table->string('type', 255)->default('waiting');
            $table->decimal('price')->nullable();
            $table->text('status_message')->nullable();
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
            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('talep_id')
                ->references('id')
                ->on('taleps')
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
        Schema::dropIfExists('agendas');
    }
};
