<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // Debe coincidir con topics.id (bigint unsigned)
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();

            $table->string('question', 500);
            // Si guardas opciones como JSON:
            $table->json('options'); // ['A','B','C','D'] o similar
            $table->unsignedInteger('correct_answer'); // 0,1,2,3 (o el índice que uses)

            // O si prefieres columnas ABCD, elimina 'options' y 'correct_answer'
            // y agrega optionA..D + correct_option (pero que coincida con tu modelo)

            $table->enum('difficulty', ['easy','medium','hard'])->default('easy');
            $table->unsignedInteger('order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->index(['topic_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
