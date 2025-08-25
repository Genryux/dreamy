<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    public function create()
    {
        // If using API, you may not need this
        return response()->json(['message' => 'Show form to create program']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'grade_level' => 'required|string',
            'days_of_the_week' => 'required|string',
            'category' => 'nullable|in:core,applied,specialized',
            'semester' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject);
    }

    public function edit(Subject $subject)
    {
        return response()->json(['message' => 'Show form to edit program', 'program' => $subject]);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'grade_level' => 'required|string',
            'days_of_the_week' => 'required|string',
            'category' => 'nullable|in:core,applied,specialized',
            'semester' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $subject->update($validated);

        return response()->json($subject);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }
}
