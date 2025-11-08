<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Exercise;

class TopicsAndExercisesSeeder extends Seeder
{
    public function run(): void
    {
        // Asegura materias base
        $math = Subject::updateOrCreate(
            ['name' => 'Matemáticas'],
            [
                'icon' => '➗',
                'description' => 'Operaciones básicas, álgebra, geometría',
                'is_active' => true,
            ]
        );

        $spanish = Subject::updateOrCreate(
            ['name' => 'Español'],
            [
                'icon' => '📚',
                'description' => 'Lectura, gramática y comprensión',
                'is_active' => true,
            ]
        );

        // Temas de Matemáticas
        $mathTheory = Topic::updateOrCreate([
            'subject_id' => $math->id,
            'title' => 'Números y Operaciones',
        ], [
            'description' => 'Repaso de sumas, restas, multiplicación y división.',
            'theory_content' => 'Contenido teórico de operaciones básicas... ',
            'type' => 'texto',
            'level' => 1,
            'order' => 1,
            'is_active' => true,
        ]);

        $mathABCD = Topic::updateOrCreate([
            'subject_id' => $math->id,
            'title' => 'Sumas básicas',
        ], [
            'description' => 'Ejercicios de selección múltiple sobre sumas.',
            'type' => 'ABCD',
            'level' => 1,
            'order' => 2,
            'is_active' => true,
        ]);

        Exercise::updateOrCreate([
            'topic_id' => $mathABCD->id,
            'question' => '¿Cuánto es 2 + 2?',
        ], [
            'options' => ['3','4','5','6'],
            'correct_answer' => 1,
            'difficulty' => 'easy',
            'order' => 1,
            'is_active' => true,
        ]);

        Exercise::updateOrCreate([
            'topic_id' => $mathABCD->id,
            'question' => '¿Cuánto es 5 + 3?',
        ], [
            'options' => ['6','7','8','9'],
            'correct_answer' => 2,
            'difficulty' => 'easy',
            'order' => 2,
            'is_active' => true,
        ]);

        // Temas de Español
        $spanishTheory = Topic::updateOrCreate([
            'subject_id' => $spanish->id,
            'title' => 'Comprensión lectora',
        ], [
            'description' => 'Estrategias para mejorar la comprensión de textos.',
            'theory_content' => 'Contenido teórico de comprensión lectora...',
            'type' => 'texto',
            'level' => 1,
            'order' => 1,
            'is_active' => true,
        ]);

        $spanishABCD = Topic::updateOrCreate([
            'subject_id' => $spanish->id,
            'title' => 'Ortografía básica',
        ], [
            'description' => 'Selecciona la opción con la ortografía correcta.',
            'type' => 'ABCD',
            'level' => 2,
            'order' => 2,
            'is_active' => true,
        ]);

        Exercise::updateOrCreate([
            'topic_id' => $spanishABCD->id,
            'question' => 'Selecciona la palabra escrita correctamente:',
        ], [
            'options' => ['Haber','A ver','Haver','A ber'],
            'correct_answer' => 1,
            'difficulty' => 'easy',
            'order' => 1,
            'is_active' => true,
        ]);
    }
}
