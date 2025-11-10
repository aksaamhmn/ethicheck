<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sentence_violations', function (Blueprint $table) {
            $table->text('snippet')->nullable()->after('violation_title');
        });
    }

    public function down(): void
    {
        Schema::table('sentence_violations', function (Blueprint $table) {
            $table->dropColumn('snippet');
        });
    }
};
