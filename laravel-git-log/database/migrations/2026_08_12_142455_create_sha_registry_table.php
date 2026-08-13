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
        Schema::create('ShaRegistry', function (Blueprint $table) {
            $table->id('ShaId');
            $table->string('RepoUrl');
            $table->string('Branch');
            $table->string('Sha');
            $table->string('CreatedAt');
            $table->unique(['RepoUrl', 'Branch', 'Sha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ShaRegistry');
    }
};
