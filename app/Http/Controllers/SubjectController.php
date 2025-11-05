<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() {
        return Subject::withCount('topics')->where('is_active', true)->get();
    }

    public function show($id) {
        $subject = Subject::with(['topics' => function($q){
            $q->where('is_active', true)->orderBy('order');
        }])->findOrFail($id);

        $progress = ['percentage' => 0]; // placeholder
        return response()->json(['subject'=>$subject,'progress'=>$progress]);
    }

    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|string|max:120',
            'icon'=>'nullable|string|max:16',
            'description'=>'nullable|string',
            'is_active'=>'boolean'
        ]);
        $subject = Subject::create($data);
        return response()->json($subject, 201);
    }

    public function update($id, Request $r) {
        $subject = Subject::findOrFail($id);
        $subject->update($r->only('name','icon','description','is_active'));
        return response()->json($subject);
    }

    public function destroy($id) {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['deleted'=>true]);
    }

    public function getUserProgress() {
        return response()->json(['data'=>[]]); // placeholder
    }
}
