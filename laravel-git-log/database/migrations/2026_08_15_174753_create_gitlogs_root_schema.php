<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schemaPath = base_path('../spec/22-git-logs-v2/18-schema.sql');
        $sql = File::get($schemaPath);
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For a root schema drop, typically we just rebuild the SQLite file.
        // There are no individual down steps.
    }
};
