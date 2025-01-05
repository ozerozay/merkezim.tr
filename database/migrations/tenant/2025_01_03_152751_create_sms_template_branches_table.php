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
        Schema::create('sms_template_branches', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('branch_id')
                ->unsigned()
                ->index();
            $table->string('type'); // Enum key değerini saklar (örn: 'appointment_one_day_before')
            $table->string('name');
            $table->text('content');
            $table->boolean('active')->default(true);
            $table->timestamps();

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
        Schema::dropIfExists('sms_template_branches');
    }
};
