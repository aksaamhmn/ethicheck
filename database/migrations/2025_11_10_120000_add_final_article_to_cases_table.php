<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (!Schema::hasColumn('cases', 'final_title')) {
                $table->string('final_title')->nullable()->after('title');
            }
            if (!Schema::hasColumn('cases', 'final_article')) {
                $table->text('final_article')->nullable()->after('article_body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (Schema::hasColumn('cases', 'final_title')) {
                $table->dropColumn('final_title');
            }
            if (Schema::hasColumn('cases', 'final_article')) {
                $table->dropColumn('final_article');
            }
        });
    }
};
