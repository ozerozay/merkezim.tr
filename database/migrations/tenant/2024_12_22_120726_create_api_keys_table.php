<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->string('merchant_id');
            $table->text('merchant_key');
            $table->text('merchant_salt');
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
