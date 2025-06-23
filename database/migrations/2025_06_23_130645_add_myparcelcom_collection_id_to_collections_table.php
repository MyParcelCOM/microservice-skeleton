<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMyparcelcomCollectionIdToCollectionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('collections', static function (Blueprint $table) {
            $table->uuid('myparcelcom_collection_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('collections', static function (Blueprint $table) {
            $table->dropIndex(['myparcelcom_collection_id']);
            $table->dropColumn('myparcelcom_collection_id');
        });
    }
}
