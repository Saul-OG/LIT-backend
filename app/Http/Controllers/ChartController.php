<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Charts data endpoint (pendiente de implementación)',
            'data' => []
        ]);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Store chart (pendiente)']);
    }

    public function update($id, Request $request)
    {
        return response()->json(['message' => 'Update chart (pendiente)']);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Delete chart (pendiente)']);
    }
}
