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
        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->bigInteger('client_id')->unsigned();
            $table->timestamp('deleted_at')->nullable();
            $table
                ->bigInteger('sale_type_id')
                ->unsigned()
                ->index();
            $table
                ->string('unique_id', 9)
                ->unique()
                ->index();
            $table->date('date');
            $table->enum('status', ['waiting', 'success', 'cancel', 'freeze']);
            $table->decimal('price');
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->decimal('price_real');
            $table->json('staffs')->nullable();
            $table->date('freeze_date')->nullable();
            $table->integer('sale_no');
            $table->text('message')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('sale_type_id')
                ->references('id')
                ->on('sale_types')
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
        Schema::dropIfExists('sale');
    }
};
