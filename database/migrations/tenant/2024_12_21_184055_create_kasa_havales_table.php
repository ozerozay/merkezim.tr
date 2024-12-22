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
        Schema::create('kasa_havales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_name');
            $table
                ->bigInteger('kasa_id')
                ->unsigned()
                ->index();
            $table->boolean('active')->default(true);
            $table->string('iban', 26);
            $table->timestamps();

            $table
                ->foreign('kasa_id')
                ->references('id')
                ->on('kasas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasa_havales');
    }
};
