<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_sentences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->unsignedInteger('sentence_index'); // 1-based order
            $table->text('text');
            $table->timestamps();
            $table->unique(['case_id', 'sentence_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_sentences');
    }
};
