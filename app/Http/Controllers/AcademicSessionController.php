<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSessionController extends Controller
{
    public function index()
    {
        $sessions = AcademicSession::withCount(['academicRecords', 'invoices'])->get();
        return view('academic_sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('academic_sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated) {
            if ($validated['is_active']) {
                // Deactivate all other sessions
                AcademicSession::query()->update(['is_active' => false]);
            }
            AcademicSession::create($validated);
        });

        return redirect()->route('academic-sessions.index')->with('success', 'Academic session created successfully.');
    }

    public function edit(AcademicSession $academicSession)
    {
        return view('academic_sessions.edit', compact('academicSession'));
    }

    public function update(Request $request, AcademicSession $academicSession)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated, $academicSession) {
            if ($validated['is_active']) {
                // Deactivate all other sessions
                AcademicSession::where('id', '!=', $academicSession->id)->update(['is_active' => false]);
            }
            $academicSession->update($validated);
        });

        return redirect()->route('academic-sessions.index')->with('success', 'Academic session updated successfully.');
    }

    public function destroy(AcademicSession $academicSession)
    {
        if ($academicSession->is_active) {
            return redirect()->route('academic-sessions.index')->with('error', 'Cannot delete an active academic session.');
        }

        if ($academicSession->academicRecords()->count() > 0 || $academicSession->invoices()->count() > 0) {
            return redirect()->route('academic-sessions.index')->with('error', 'Cannot delete this session because it has associated records.');
        }

        $academicSession->delete();

        return redirect()->route('academic-sessions.index')->with('success', 'Academic session deleted successfully.');
    }
}
