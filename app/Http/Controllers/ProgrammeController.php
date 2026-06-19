<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programme::withCount('students')->get();
        return view('programmes.index', compact('programmes'));
    }

    public function create()
    {
        return view('programmes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:programmes,code',
        ]);

        Programme::create($validated);

        return redirect()->route('programmes.index')->with('success', 'Programme created successfully.');
    }

    public function edit(Programme $programme)
    {
        return view('programmes.edit', compact('programme'));
    }

    public function update(Request $request, Programme $programme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:programmes,code,'.$programme->id,
        ]);

        $programme->update($validated);

        return redirect()->route('programmes.index')->with('success', 'Programme updated successfully.');
    }

    public function destroy(Programme $programme)
    {
        if ($programme->students()->count() > 0) {
            return redirect()->route('programmes.index')->with('error', 'Cannot delete a programme that has students associated with it.');
        }

        $programme->delete();

        return redirect()->route('programmes.index')->with('success', 'Programme deleted successfully.');
    }
}
