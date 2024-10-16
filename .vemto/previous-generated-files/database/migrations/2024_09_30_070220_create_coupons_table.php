<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('service_category_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('client_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('code', 20)->unique();
            $table->boolean('discount_type');
            $table->integer('count')->nullable();
            $table->integer('discount_amount');
            $table->date('end_date')->nullable();
            $table->decimal('min_order')->nullable();
            $table->bigInteger('category_id')->unsigned();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('service_category_id')
                ->references('id')
                ->on('service_categories');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table
                ->foreign('client_id')
                ->references('id')
                ->on('users');
            $table
                ->foreign('category_id')
                ->references('id')
                ->on('service_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
