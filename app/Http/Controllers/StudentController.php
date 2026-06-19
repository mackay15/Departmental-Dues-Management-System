<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Programme;
use App\Models\AcademicLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['programme', 'currentLevel']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('student_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
        }

        $students = $query->paginate(15);
        
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programmes = Programme::all();
        $levels = AcademicLevel::all();
        return view('students.create', compact('programmes', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|string|unique:students,student_number',
            'index_number' => 'required|string|unique:students,index_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'programme_id' => 'required|exists:programmes,id',
            'current_level_id' => 'required|exists:academic_levels,id',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Handle Photo Upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Create User Account for Student
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['student_number']), // Default password is student number
        ]);
        $user->assignRole('Student');

        // Create Student Profile
        $student = Student::create([
            'student_number' => $validated['student_number'],
            'index_number' => $validated['index_number'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'other_names' => $validated['other_names'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'photo_path' => $photoPath,
            'programme_id' => $validated['programme_id'],
            'current_level_id' => $validated['current_level_id'],
            'status' => 'active',
            'user_id' => $user->id,
        ]);

        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['programme', 'currentLevel', 'academicRecords.academicSession', 'academicRecords.academicLevel']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $programmes = Programme::all();
        $levels = AcademicLevel::all();
        return view('students.edit', compact('student', 'programmes', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'phone' => 'nullable|string|max:20',
            'programme_id' => 'required|exists:programmes,id',
            'current_level_id' => 'required|exists:academic_levels,id',
            'status' => 'required|in:active,suspended,deferred,graduated',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $student->photo_path = $request->file('photo')->store('photos', 'public');
        }

        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'other_names' => $validated['other_names'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'programme_id' => $validated['programme_id'],
            'current_level_id' => $validated['current_level_id'],
            'status' => $validated['status'],
        ]);

        // Update User Account if needed
        if ($student->user) {
            $student->user->update([
                'name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->email,
            ]);
        }

        return redirect()->route('students.show', $student)->with('success', 'Student profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Instead of hard delete, we set status to suspended/inactive 
        // as per the requirement "No academic records are deleted".
        $student->update(['status' => 'suspended']);

        return redirect()->route('students.index')->with('success', 'Student has been suspended. Academic records are preserved.');
    }
}
