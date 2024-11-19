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
        Schema::table('transactions', function (Blueprint $table) {
            $table
                ->bigInteger('masraf_id')
                ->unsigned()
                ->nullable()
                ->after('type');
            $table
                ->foreign('masraf_id')
                ->references('id')
                ->on('masraf')
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('masraf_id');
            $table->dropForeign('transactions_masraf_id_foreign');
            $table->dropColumn('masraf_id');
            $table
                ->bigInteger('masraf_id')
                ->unsigned()
                ->index()
                ->after('type');
            $table->dropColumn('masraf_id');
            $table
                ->bigInteger('masraf_id')
                ->unsigned()
                ->index()
                ->after('type');
            $table->dropColumn('masraf_id');
            $table
                ->bigInteger('masraf_id')
                ->unsigned()
                ->index()
                ->after('type');
            $table->dropColumn('masraf_id');
            $table
                ->bigInteger('masraf_id')
                ->unsigned()
                ->index()
                ->after('type');
        });
    }
};
