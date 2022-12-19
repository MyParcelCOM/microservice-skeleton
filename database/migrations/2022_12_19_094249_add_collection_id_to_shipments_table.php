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
        Schema::table('shipments', function (Blueprint $table) {
            $table->uuid('collection_id')->nullable();

            $table->foreign('collection_id')
                ->references('id')
                ->on('collections')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->index('collection_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('collection_id');
        });
    }
};
