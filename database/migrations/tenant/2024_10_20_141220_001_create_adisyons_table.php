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
        Schema::create('adisyons', function (Blueprint $table) {
            $table->string('unique_id', 9)->unique();
            $table
                ->bigInteger('branch_id')
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
            $table
                ->bigInteger('coupon_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->date('date');
            $table->json('staff_ids')->nullable();
            $table->string('message', 255);
            $table->decimal('price');
            $table->decimal('coupon_price')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->id();

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
                ->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
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
        Schema::dropIfExists('adisyons');
    }
};
