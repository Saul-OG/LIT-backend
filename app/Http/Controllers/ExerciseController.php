<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Topic;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function getByTopic($topicId)
    {
        return Exercise::where('topic_id', $topicId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'topic_id' => 'required|exists:topics,id',
            'question' => 'required|string|max:500',
            'options'  => 'required|array|size:4',
            'options.*'=> 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
            'difficulty' => 'nullable|string',
            'level' => 'nullable|integer|min:1|max:3',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $topicLevel = optional(\App\Models\Topic::find($data['topic_id']))->level ?? 1;

        $ex = Exercise::create(array_merge([
            'difficulty' => 'easy',
            'level' => $data['level'] ?? $topicLevel,
            'order' => 1,
            'is_active' => true,
        ], $data));

        return response()->json($ex, 201);
    }

    public function update($id, Request $r)
    {
        $ex = Exercise::findOrFail($id);
        $ex->update($r->only('question','options','correct_answer','difficulty','order','is_active'));
        return response()->json($ex);
    }

    public function destroy($id)
    {
        $ex = Exercise::findOrFail($id);
        $ex->delete();
        return response()->json(['deleted'=>true]);
    }

    // Carga masiva de ejercicios ABCD para un tópico
    public function bulkStore($topicId, Request $r)
    {
        $topic = Topic::findOrFail($topicId);
        $data = $r->validate([
            'items' => 'required|array|min:1',
            'items.*.question' => 'required|string|max:500',
            'items.*.options'  => 'required|array|size:4',
            'items.*.options.*'=> 'required|string',
            'items.*.correct_answer' => 'required|integer|min:0|max:3',
            'items.*.difficulty' => 'nullable|string',
            'items.*.level' => 'nullable|integer|min:1|max:3',
        ]);

        $created = [];
        foreach ($data['items'] as $idx => $item) {
            $created[] = Exercise::create([
                'topic_id'       => $topic->id,
                'question'       => $item['question'],
                'options'        => $item['options'],
                'correct_answer' => (int) $item['correct_answer'],
                'difficulty'     => $item['difficulty'] ?? 'easy',
                'level'          => $item['level'] ?? ($topic->level ?? 1),
                'order'          => $idx + 1,
                'is_active'      => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => count($created),
            'ids' => collect($created)->pluck('id'),
        ], 201);
    }

    public function submitAnswer($exerciseId, Request $r)
    {
        $r->validate(['answer' => 'required']);
        $ex = Exercise::findOrFail($exerciseId);

        $raw = $r->input('answer');
        // Normaliza la respuesta a índice 0..3
        if (is_numeric($raw)) {
            $answer = (int) $raw;
        } else {
            $map = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];
            $answer = $map[$raw] ?? -1;
        }

        $isCorrect = ($answer === (int) $ex->correct_answer);

        // Placeholders de progreso/vidas (ajústalo a tu lógica real)
        $lives = $isCorrect ? 3 : 2;
        $progress = $isCorrect ? 5 : 0; // porcentaje o puntos de progreso

        // Registrar envío del usuario (para métricas)
        $userId = optional($r->user())->id; // grupo protegido, pero por seguridad
        if ($userId) {
            ExerciseSubmission::create([
                'user_id' => $userId,
                'exercise_id' => $ex->id,
                'is_correct' => $isCorrect,
                'points' => $isCorrect ? 10 : 0,
            ]);
        }

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => (int) $ex->correct_answer,
            'message' => $isCorrect ? '¡Correcto!' : 'Respuesta incorrecta',
            'lives' => $lives,
            'life_gained' => $isCorrect,
            'progress' => $progress,
        ]);
    }
}
