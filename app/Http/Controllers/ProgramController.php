<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return response()->json($programs);
    }

    public function create()
    {
        // If using API, you may not need this
        return response()->json(['message' => 'Show form to create program']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:programs,code',
            'name' => 'required|string|max:255',
        ]);

        $program = Program::create($validated);

        return response()->json($program, 201);
    }

    public function show(Program $program)
    {
        return response()->json($program);
    }

    public function edit(Program $program)
    {
        return response()->json(['message' => 'Show form to edit program', 'program' => $program]);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:programs,code,' . $program->id,
            'name' => 'required|string|max:255',
        ]);

        $program->update($validated);

        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }
}
