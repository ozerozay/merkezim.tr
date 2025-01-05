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
        Schema::create('admin_home_widgets', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->string('type')->unique(); // WidgetType Enum'un değeri
            $table->string('title'); // Başlık
            $table->integer('order')->default(0); // Sıralama
            $table->boolean('visible')->default(true); // Açık/Kapalı durumu
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_home_widgets');
    }
};
