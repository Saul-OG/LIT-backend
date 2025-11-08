<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;

class ReportController extends Controller
{
    public function getGeneralStats()
    {
        $totalExercises = Exercise::count();
        $correct = (int) ExerciseSubmission::where('is_correct', true)->count();
        $wrong   = (int) ExerciseSubmission::where('is_correct', false)->count();
        $accuracy = ($correct + $wrong) > 0 ? round(($correct / ($correct + $wrong)) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'message' => 'General stats',
            'data' => [
                'total_exercises'  => $totalExercises,
                'correct_answers'  => $correct,
                'wrong_answers'    => $wrong,
                'accuracy'         => $accuracy,
            ]
        ]);
    }

    public function getSubjectStats()
    {
        // Placeholder: progreso promedio por materia (0%)
        // Alternativa simple: porcentaje de temas que tienen al menos un ejercicio
        $subjects = Subject::with(['topics' => function($q){
            $q->withCount('exercises');
        }])->get();

        $data = $subjects->map(function($s){
            $totalTopics = $s->topics->count();
            if ($totalTopics === 0) {
                $avg = 0;
            } else {
                $withExercises = $s->topics->where('exercises_count', '>', 0)->count();
                $avg = round(($withExercises / $totalTopics) * 100, 1);
            }
            return [
                'subject_name' => $s->name,
                'avg_progress' => $avg,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Average progress by subject (proxy)',
            'data' => $data,
        ]);
    }

    public function getLivesStats()
    {
        $avgLives = (int) round((float) User::avg('lives'));
        $minLives = (int) User::min('lives');
        $maxLives = (int) User::max('lives');

        return response()->json([
            'success' => true,
            'message' => 'Lives stats',
            'data' => [
                'avg_lives' => $avgLives,
                'min_lives' => $minLives,
                'max_lives' => $maxLives,
            ]
        ]);
    }

    public function getNewUsersPerMonth()
    {
        // Devuelve registros por día (últimos 14 días)
        $days = 14;
        $start = Carbon::now()->startOfDay()->subDays($days - 1);

        $raw = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c','d');

        $labels = [];
        $values = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $labels[] = $date;
            $values[] = (int) ($raw[$date] ?? 0);
        }

        return response()->json([
            'success' => true,
            'message' => 'New users per day (last 14 days)',
            'data' => [
                'labels' => $labels,
                'values' => $values,
            ]
        ]);
    }

    public function getMostViewedSubjects()
    {
        // Sin tracking de vistas, usamos cantidad de ejercicios por materia como proxy
        $subjects = Subject::with(['topics' => function($q){
            $q->withCount('exercises');
        }])->get();

        $labels = [];
        $values = [];
        foreach ($subjects as $s) {
            $labels[] = $s->name;
            $values[] = (int) $s->topics->sum('exercises_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Subjects by total exercises (proxy)',
            'data' => [
                'labels' => $labels,
                'values' => $values,
            ]
        ]);
    }
}
