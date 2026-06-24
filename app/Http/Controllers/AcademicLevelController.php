<?php

namespace App\Http\Controllers;

use App\Models\AcademicLevel;
use Illuminate\Http\Request;

class AcademicLevelController extends Controller
{
    public function index()
    {
        $levels = AcademicLevel::withCount('students')->get();
        return view('academic_levels.index', compact('levels'));
    }

    public function create()
    {
        return view('academic_levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|unique:academic_levels,numeric_value',
        ]);

        AcademicLevel::create($validated);

        return redirect()->route('academic-levels.index')->with('success', 'Academic level created successfully.');
    }

    public function edit(AcademicLevel $academicLevel)
    {
        return view('academic_levels.edit', compact('academicLevel'));
    }

    public function update(Request $request, AcademicLevel $academicLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_value' => 'required|integer|unique:academic_levels,numeric_value,'.$academicLevel->id,
        ]);

        $academicLevel->update($validated);

        return redirect()->route('academic-levels.index')->with('success', 'Academic level updated successfully.');
    }

    public function destroy(AcademicLevel $academicLevel)
    {
        if ($academicLevel->students()->count() > 0) {
            return redirect()->route('academic-levels.index')->with('error', 'Cannot delete an academic level that has students associated with it.');
        }

        $academicLevel->delete();

        return redirect()->route('academic-levels.index')->with('success', 'Academic level deleted successfully.');
    }
}
