<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Models\Programme;
use App\Models\AcademicLevel;
use App\Models\Student;
use App\Models\PromotionLog;
use App\Models\StudentAcademicRecord;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentPromotionController extends Controller
{
    public function index()
    {
        $logs = PromotionLog::with(['academicSession', 'promotedBy'])->latest()->paginate(20);
        return view('promotions.index', compact('logs'));
    }

    public function preview(Request $request)
    {
        $sessions = AcademicSession::all();
        $programmes = Programme::all();
        $levels = AcademicLevel::orderBy('numeric_value')->get();

        $students = [];
        $selectedSession = null;
        $selectedProgramme = null;
        $selectedLevel = null;
        $nextLevel = null;

        if ($request->has(['academic_session_id', 'programme_id', 'from_level_id'])) {
            $selectedSession = AcademicSession::find($request->academic_session_id);
            $selectedProgramme = Programme::find($request->programme_id);
            $selectedLevel = AcademicLevel::find($request->from_level_id);

            // Find next level based on numeric value
            if ($selectedLevel) {
                $nextLevel = AcademicLevel::where('numeric_value', '>', $selectedLevel->numeric_value)
                                          ->orderBy('numeric_value')
                                          ->first();
            }

            if ($selectedSession && $selectedProgramme && $selectedLevel) {
                $studentsQuery = Student::where('programme_id', $selectedProgramme->id)
                                        ->where('current_level_id', $selectedLevel->id)
                                        ->where('status', 'active');
                
                $studentsList = $studentsQuery->get();

                // Check eligibility based on outstanding balances for the selected session
                foreach ($studentsList as $student) {
                    $invoice = Invoice::where('student_id', $student->id)
                                      ->where('academic_session_id', $selectedSession->id)
                                      ->first();
                    
                    // A student is eligible if they have no invoice (no dues) or their invoice balance is 0.00
                    $student->has_balance = $invoice && $invoice->balance > 0;
                    $student->outstanding_balance = $invoice ? $invoice->balance : 0.00;
                    $student->is_eligible = !$student->has_balance;
                    
                    $students[] = $student;
                }
            }
        }

        return view('promotions.preview', compact(
            'sessions', 'programmes', 'levels', 'students', 
            'selectedSession', 'selectedProgramme', 'selectedLevel', 'nextLevel'
        ));
    }

    public function promote(Request $request)
    {
        $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'programme_id' => 'required|exists:programmes,id',
            'from_level_id' => 'required|exists:academic_levels,id',
            'to_level_id' => 'nullable|exists:academic_levels,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $sessionId = $request->academic_session_id;
        $toLevelId = $request->to_level_id;
        $studentIds = $request->student_ids;

        $promotedCount = 0;
        $promotionDetails = [];

        DB::transaction(function () use ($studentIds, $sessionId, $toLevelId, &$promotedCount, &$promotionDetails) {
            foreach ($studentIds as $studentId) {
                $student = Student::find($studentId);
                
                if (!$student) continue;

                // Double check eligibility
                $invoice = Invoice::where('student_id', $student->id)
                                  ->where('academic_session_id', $sessionId)
                                  ->first();
                
                if ($invoice && $invoice->balance > 0) {
                    continue; // Skip ineligible students
                }

                $previousLevelId = $student->current_level_id;

                // Create academic record
                StudentAcademicRecord::create([
                    'student_id' => $student->id,
                    'academic_session_id' => $sessionId,
                    'academic_level_id' => $previousLevelId,
                    'programme_id' => $student->programme_id,
                    'status' => 'promoted',
                ]);

                // Update student's current level
                if ($toLevelId) {
                    $student->current_level_id = $toLevelId;
                } else {
                    // If no next level, they have graduated
                    $student->status = 'graduated';
                }
                $student->save();

                $promotionDetails[] = [
                    'student_id' => $student->id,
                    'from_level_id' => $previousLevelId,
                    'to_level_id' => $toLevelId,
                ];

                $promotedCount++;
            }

            if ($promotedCount > 0) {
                // Generate log
                $session = AcademicSession::find($sessionId);
                $desc = "Promoted {$promotedCount} students from session " . $session->name;

                PromotionLog::create([
                    'academic_session_id' => $sessionId,
                    'promoted_by' => Auth::id(),
                    'description' => $desc,
                    'details' => $promotionDetails,
                ]);
            }
        });

        if ($promotedCount === 0) {
            return back()->with('error', 'No eligible students were successfully promoted.');
        }

        return redirect()->route('promotions.index')->with('success', "Successfully promoted {$promotedCount} students.");
    }
}
