<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIdDefaultValueToCollectionsTable extends Migration
{
    public function up(): void
    {
        // IMPORTANT: Ensure the `uuid-ossp` extension is enabled on the DB before running this migration.
        Schema::table('collections', function (Blueprint $table) {
            DB::statement('ALTER TABLE collections ALTER id SET DEFAULT uuid_generate_v4()');
        });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            DB::statement('ALTER TABLE collections ALTER COLUMN id DROP DEFAULT');
        });
    }
}
