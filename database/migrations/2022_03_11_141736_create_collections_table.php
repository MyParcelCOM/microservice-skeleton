<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('collection_date');
            $table->string('collection_time_from');
            $table->string('collection_time_to');
            $table->json('address_json');
            $table->json('contact_json');
            $table->string('tracking_code')->nullable();
            $table->dateTime('registered_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
}
