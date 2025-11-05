<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
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
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $ex = Exercise::create(array_merge([
            'difficulty' => 'easy',
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

    public function submitAnswer($exerciseId, Request $r)
    {
        $data = $r->validate(['answer' => 'required|integer|min:0|max:3']);
        $ex = Exercise::findOrFail($exerciseId);

        $correct = ((int)$data['answer'] === (int)$ex->correct_answer);

        // placeholders de métricas
        return response()->json([
            'correct' => $correct,
            'message' => $correct ? '¡Correcto!' : 'Respuesta incorrecta',
            'data' => [
                'points' => $correct ? 10 : 0,
                'level'  => 1,
                'lives'  => $correct ? 3 : 2,
                'streak_days' => $correct ? 1 : 0,
            ]
        ]);
    }
}
