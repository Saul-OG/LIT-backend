<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function index($subjectId)
    {
        return Topic::where('subject_id', $subjectId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function show($id)
    {
        return Topic::with('exercises')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $rulesBase = [
            'subject_id'  => 'required|exists:subjects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:1',
            'type'        => 'required|in:texto,video,ABCD',
            'is_active'   => 'boolean',
        ];
        $rulesExtra = [];
        if ($request->type === 'texto') {
            $rulesExtra['theory_content'] = 'required|string';
        } elseif ($request->type === 'video') {
            $rulesExtra['video_url'] = 'required|url';
        } elseif ($request->type === 'ABCD') {
            $rulesExtra = [
                'optionA' => 'required|string',
                'optionB' => 'required|string',
                'optionC' => 'required|string',
                'optionD' => 'required|string',
                'correct_option' => 'required|in:A,B,C,D',
            ];
        }

        $v = Validator::make($request->all(), array_merge($rulesBase,$rulesExtra));
        if ($v->fails()) {
            return response()->json(['success'=>false,'message'=>'Errores de validación','errors'=>$v->errors()], 422);
        }

        $topic = Topic::create($request->all());

        if ($topic->type === 'ABCD') {
            $options = [
                $request->optionA,
                $request->optionB,
                $request->optionC,
                $request->optionD,
            ];
            $map = ['A'=>0,'B'=>1,'C'=>2,'D'=>3];
            Exercise::create([
                'topic_id'       => $topic->id,
                'question'       => $topic->title,
                'options'        => $options,
                'correct_answer' => $map[$request->correct_option] ?? 0,
                'difficulty'     => 'easy',
                'order'          => 1,
                'is_active'      => true,
            ]);
        }

        return response()->json(['success'=>true,'data'=>$topic], 201);
    }

    public function update($id, Request $r)
    {
        $topic = Topic::findOrFail($id);
        $topic->update($r->only([
            'subject_id','title','description','theory_content','video_url',
            'type','order','is_active','optionA','optionB','optionC','optionD','correct_option'
        ]));
        return response()->json($topic);
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();
        return response()->json(['deleted'=>true]);
    }
}
