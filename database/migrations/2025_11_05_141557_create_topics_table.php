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
        Schema::create('topics', function (Blueprint $table) {
            // Asegura mismo engine/charset que el resto (opcional pero buena práctica)
            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id(); // bigint unsigned auto_increment
            // subject_id debe referenciar subjects(id) ya existente
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();

            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->longText('theory_content')->nullable();
            $table->string('video_url')->nullable();

            $table->enum('type', ['texto','video','ABCD'])->default('texto');

            $table->unsignedInteger('order')->default(1);
            $table->boolean('is_active')->default(true);

            // Campos opcionales para ABCD (no afectan la FK de exercises)
            $table->string('optionA')->nullable();
            $table->string('optionB')->nullable();
            $table->string('optionC')->nullable();
            $table->string('optionD')->nullable();
            $table->enum('correct_option', ['A','B','C','D'])->nullable();

            $table->timestamps();

            // Índices útiles
            $table->index(['subject_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
