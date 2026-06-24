<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Programme;
use App\Models\AcademicLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            $query->where('index_number', 'like', "%{$search}%")
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
            'password' => Hash::make($validated['index_number']), // Default password is index number
        ]);
        $user->assignRole('Student');

        // Create Student Profile
        $student = Student::create([
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
        $user = auth()->user();
        if ($user && $user->hasRole('Student') && $student->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

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

    /**
     * Show the bulk student import form.
     */
    public function showImportForm()
    {
        return view('students.import');
    }

    /**
     * Download the standard CSV template for student import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_import_template.csv"',
        ];

        $columns = [
            'index_number',
            'first_name',
            'last_name',
            'other_names',
            'email',
            'phone',
            'programme_code',
            'level_numeric'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, [
                '032024001',
                'John',
                'Doe',
                'Kofi',
                'johndoe@student.edu.gh',
                '0240000001',
                'CS', // Code of program
                '100' // Numeric level
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process the bulk student records import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xlsx|max:5120', // Max 5MB
        ]);

        $file = $request->file('file');
        
        try {
            $import = new StudentsImport();
            Excel::import($import, $file);
            $rows = $import->getRows();
        } catch (\Exception $e) {
            return back()->with('error', 'Error parsing file: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return back()->with('error', 'The uploaded file contains no data or the heading row is missing/invalid.');
        }

        $errors = [];
        $validatedData = [];

        // Eager load programmes and levels to avoid N+1 queries during validation
        $programmes = Programme::all()->keyBy('code');
        $levels = AcademicLevel::all()->keyBy('numeric_value');

        // Track seen identifiers to check for duplicate entries *within* the uploaded file itself
        $seenIndexNumbers = [];
        $seenEmails = [];
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because Excel heading is row 1 and index is 0-based

            // Normalize and cast fields before validation
            $row = array_map(function($val) {
                return is_string($val) ? trim($val) : (is_null($val) ? null : (string) $val);
            }, $row);

            // Ensure index_number and phone are strings
            $row['index_number'] = (string) $row['index_number'];
            if (isset($row['phone'])) {
                $row['phone'] = (string) $row['phone'];
            }

            // Normalize programme code to uppercase for case-insensitive matching
            if (isset($row['programme_code'])) {
                $row['programme_code'] = strtoupper($row['programme_code']);
            }

            // Cast level_numeric to integer
            if (isset($row['level_numeric'])) {
                $row['level_numeric'] = (int) $row['level_numeric'];
            }

            // Simple required check
            $requiredFields = ['index_number', 'first_name', 'last_name', 'email', 'programme_code', 'level_numeric'];
            $missing = [];
            foreach ($requiredFields as $field) {
                if (!isset($row[$field]) || $row[$field] === '') {
                    $missing[] = str_replace('_', ' ', $field);
                }
            }

            if (!empty($missing)) {
                $errors[] = "Row {$rowNum}: Missing required field(s): " . implode(', ', $missing);
                continue;
            }

            // Uniqueness in file check

            if (in_array($row['index_number'], $seenIndexNumbers)) {
                $errors[] = "Row {$rowNum}: Duplicate Index Number '{$row['index_number']}' found within the file.";
            }
            $seenIndexNumbers[] = $row['index_number'];

            if (in_array($row['email'], $seenEmails)) {
                $errors[] = "Row {$rowNum}: Duplicate Email '{$row['email']}' found within the file.";
            }
            $seenEmails[] = $row['email'];

            // Validation rules
            $validator = Validator::make($row, [
                'index_number'   => 'required|string|unique:students,index_number',
                'first_name'     => 'required|string|max:255',
                'last_name'      => 'required|string|max:255',
                'other_names'    => 'nullable|string|max:255',
                'email'          => 'required|email|unique:students,email|unique:users,email',
                'phone'          => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $err) {
                    $errors[] = "Row {$rowNum}: {$err}";
                }
            }

            // Programme check
            $progCode = $row['programme_code'];
            if (!$programmes->has($progCode)) {
                $errors[] = "Row {$rowNum}: Programme with code '{$progCode}' does not exist.";
            }

            // Academic Level check
            $levelVal = $row['level_numeric'];
            if (!$levels->has($levelVal)) {
                $errors[] = "Row {$rowNum}: Academic level '{$levelVal}' does not exist.";
            }

            if (empty($errors)) {
                $validatedData[] = [
                    'index_number'     => $row['index_number'],
                    'first_name'       => $row['first_name'],
                    'last_name'        => $row['last_name'],
                    'other_names'      => $row['other_names'] ?? null,
                    'email'            => $row['email'],
                    'phone'            => $row['phone'] ?? null,
                    'programme_id'     => $programmes->get($progCode)->id,
                    'current_level_id' => $levels->get($levelVal)->id,
                ];
            }
        }

        if (!empty($errors)) {
            return back()->withInput()->with('import_errors', $errors);
        }

        // Database transaction to insert
        $importedCount = 0;
        try {
            DB::transaction(function() use ($validatedData, &$importedCount) {
                foreach ($validatedData as $data) {
                    // Create User Account
                    $user = User::create([
                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['index_number']), // Default password is index number
                    ]);
                    $user->assignRole('Student');

                    // Create Student Profile
                    Student::create([
                        'index_number' => $data['index_number'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'other_names' => $data['other_names'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'programme_id' => $data['programme_id'],
                        'current_level_id' => $data['current_level_id'],
                        'status' => 'active',
                        'user_id' => $user->id,
                    ]);

                    $importedCount++;
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during database insertion: ' . $e->getMessage());
        }

        return redirect()->route('students.index')
            ->with('success', "Successfully imported {$importedCount} student records.");
    }
}
