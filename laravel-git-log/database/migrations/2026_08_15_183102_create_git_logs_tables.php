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
        if (File::exists($schemaPath)) {
            $sql = File::get($schemaPath);
            // Sqlite PDO doesn't like multi-statement prepared queries if they contain PRAGMA
            // We just execute it unprepared
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('git_logs_tables');
    }
};
