<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\AcademicSession;
use App\Models\Programme;
use App\Models\AcademicLevel;
use Illuminate\Http\Request;

class DueController extends Controller
{
    public function index()
    {
        $dues = Due::with(['academicSession', 'programme', 'academicLevel'])->get();
        return view('dues.index', compact('dues'));
    }

    public function create()
    {
        $sessions = AcademicSession::all();
        $programmes = Programme::all();
        $levels = AcademicLevel::all();
        return view('dues.create', compact('sessions', 'programmes', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'academic_level_id' => 'nullable|exists:academic_levels,id',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        Due::create($validated);

        return redirect()->route('dues.index')->with('success', 'Due created successfully.');
    }

    public function edit(Due $due)
    {
        $sessions = AcademicSession::all();
        $programmes = Programme::all();
        $levels = AcademicLevel::all();
        return view('dues.edit', compact('due', 'sessions', 'programmes', 'levels'));
    }

    public function update(Request $request, Due $due)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'academic_level_id' => 'nullable|exists:academic_levels,id',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $due->update($validated);

        return redirect()->route('dues.index')->with('success', 'Due updated successfully.');
    }

    public function destroy(Due $due)
    {
        // Check if there are invoice items associated with this due
        // Invoices hold records, if we delete the due, it might cascade and delete invoice items,
        // which would mess up financial records.
        if (\DB::table('invoice_items')->where('due_id', $due->id)->exists()) {
            return redirect()->route('dues.index')->with('error', 'Cannot delete this due because it has been invoiced to students.');
        }

        $due->delete();

        return redirect()->route('dues.index')->with('success', 'Due deleted successfully.');
    }
}
