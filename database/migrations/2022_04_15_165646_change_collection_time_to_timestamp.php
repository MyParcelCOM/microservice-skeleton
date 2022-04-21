<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Prepare new columns
        Schema::table('collections', function (Blueprint $table) {
            $table->renameColumn('collection_time_from', 'collection_time_from_old');
            $table->renameColumn('collection_time_to', 'collection_time_to_old');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->timestamp('collection_time_from')->nullable();
            $table->timestamp('collection_time_to')->nullable();
        });

        // Migrate data
        DB::table('collections')->get()->each(function ($row) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $row->collection_date);
            $timeFrom = explode(':', $row->collection_time_from_old);
            $timeTo = explode(':', $row->collection_time_to_old);

            DB::table('collections')->where('id', $row->id)->update([
                'collection_time_from' => $date->clone()->setTime((int) $timeFrom[0], (int) $timeFrom[1]),
                'collection_time_to'   => $date->clone()->setTime((int) $timeTo[0], (int) $timeTo[1]),
            ]);
        });

        // Drop old columns
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['collection_date', 'collection_time_from_old', 'collection_time_to_old']);
        });
        DB::statement('ALTER TABLE collections ALTER COLUMN collection_time_from SET NOT NULL');
        DB::statement('ALTER TABLE collections ALTER COLUMN collection_time_to SET NOT NULL');
    }

    public function down(): void
    {
        // Prepare new columns
        Schema::table('collections', function (Blueprint $table) {
            $table->renameColumn('collection_time_from', 'collection_time_from_old');
            $table->renameColumn('collection_time_to', 'collection_time_to_old');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->string('collection_date')->nullable();
            $table->string('collection_time_from')->nullable();
            $table->string('collection_time_to')->nullable();
        });

        // Migrate data
        DB::table('collections')->get()->each(function ($row) {
            $timeFrom = Carbon::createFromTimestamp($row->collection_time_from_old);
            $timeTo = Carbon::createFromTimestamp($row->collection_time_to_old);

            DB::table('collections')->where('id', $row->id)->update([
                'collection_date'      => $timeFrom->format('Y-m-d'),
                'collection_time_from' => $timeFrom->format('H:i'),
                'collection_time_to'   => $timeTo->format('H:i'),
            ]);
        });

        // Drop old columns
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['collection_time_from_old', 'collection_time_to_old']);
        });
        DB::statement('ALTER TABLE collections ALTER COLUMN collection_date SET NOT NULL');
        DB::statement('ALTER TABLE collections ALTER COLUMN collection_time_from SET NOT NULL');
        DB::statement('ALTER TABLE collections ALTER COLUMN collection_time_to SET NOT NULL');
    }
};
