<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('article_body'); // full article (optional redundancy)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
