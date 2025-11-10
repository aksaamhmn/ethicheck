<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentence_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_sentence_id')->constrained('case_sentences')->cascadeOnDelete();
            $table->string('violation_code')->nullable(); // e.g. KEJ-3
            $table->string('violation_title'); // short label
            $table->text('description'); // penjelasan
            $table->string('legal_basis')->nullable(); // Dasar hukum/etika (multi-line possible, store as text?)
            $table->enum('severity', ['minor', 'major'])->default('minor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentence_violations');
    }
};
