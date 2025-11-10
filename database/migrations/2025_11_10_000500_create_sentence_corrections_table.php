<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sentence_corrections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_sentence_id');
            $table->text('text');
            $table->boolean('is_correct')->default(false);
            $table->text('rationale')->nullable();
            $table->timestamps();

            $table->foreign('case_sentence_id')
                ->references('id')->on('case_sentences')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentence_corrections');
    }
};
