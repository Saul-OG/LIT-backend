<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getGeneralStats()
    {
        return response()->json([
            'success' => true,
            'message' => 'General stats placeholder',
            'data' => [
                'users' => 0,
                'subjects' => 0,
                'topics' => 0,
            ]
        ]);
    }

    public function getSubjectStats()
    {
        return response()->json([
            'success' => true,
            'message' => 'Subject stats placeholder',
            'data' => []
        ]);
    }

    public function getLivesStats()
    {
        return response()->json([
            'success' => true,
            'message' => 'Lives stats placeholder',
            'data' => []
        ]);
    }

    public function getNewUsersPerMonth()
    {
        return response()->json([
            'success' => true,
            'message' => 'New users per month placeholder',
            'data' => []
        ]);
    }

    public function getMostViewedSubjects()
    {
        return response()->json([
            'success' => true,
            'message' => 'Most viewed subjects placeholder',
            'data' => []
        ]);
    }
}
