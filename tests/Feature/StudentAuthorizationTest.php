<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Programme;
use App\Models\AcademicLevel;

class StudentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        \Spatie\Permission\Models\Role::create(['name' => 'HOD']);
        \Spatie\Permission\Models\Role::create(['name' => 'Finance Officer']);
        \Spatie\Permission\Models\Role::create(['name' => 'Student']);
    }

    public function test_hod_can_access_student_management_routes(): void
    {
        $hod = User::factory()->create();
        $hod->assignRole('HOD');

        // Can view creation form
        $response = $this->actingAs($hod)->get(route('students.create'));
        $response->assertStatus(200);

        // Can view import form
        $response = $this->actingAs($hod)->get(route('students.import'));
        $response->assertStatus(200);

        // Create prerequisites for creating a student
        $programme = Programme::create(['name' => 'Computer Science', 'code' => 'CS']);
        $level = AcademicLevel::create(['name' => 'Level 100', 'numeric_value' => 100]);

        // Can store a student
        $response = $this->actingAs($hod)->post(route('students.store'), [
            'index_number' => 'STU100',
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'phone' => '0240000001',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['index_number' => 'STU100']);

        $student = Student::where('index_number', 'STU100')->first();

        // Can edit a student
        $response = $this->actingAs($hod)->get(route('students.edit', $student));
        $response->assertStatus(200);

        // Can update a student
        $response = $this->actingAs($hod)->put(route('students.update', $student), [
            'first_name' => 'Alice Updated',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('students.show', $student));
        $this->assertDatabaseHas('students', ['first_name' => 'Alice Updated']);

        // Can delete a student
        $response = $this->actingAs($hod)->delete(route('students.destroy', $student));
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', [
            'index_number' => 'STU100',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'alice@example.com',
        ]);
    }

    public function test_finance_officer_cannot_access_student_management_routes(): void
    {
        $finance = User::factory()->create();
        $finance->assignRole('Finance Officer');

        // Create a student direct in DB to test edit/update/delete
        $programme = Programme::create(['name' => 'Computer Science', 'code' => 'CS']);
        $level = AcademicLevel::create(['name' => 'Level 100', 'numeric_value' => 100]);
        $student = Student::create([
            'user_id' => $finance->id,
            'index_number' => 'STU200',
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'email' => 'bob@example.com',
            'phone' => '0240000002',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);

        // CANNOT access index
        $response = $this->actingAs($finance)->get(route('students.index'));
        $response->assertStatus(403);

        // CANNOT access show
        $response = $this->actingAs($finance)->get(route('students.show', $student));
        $response->assertStatus(403);

        // CANNOT access create form
        $response = $this->actingAs($finance)->get(route('students.create'));
        $response->assertStatus(403);

        // CANNOT access import form
        $response = $this->actingAs($finance)->get(route('students.import'));
        $response->assertStatus(403);

        // CANNOT store a student
        $response = $this->actingAs($finance)->post(route('students.store'), [
            'index_number' => 'STU201',
            'first_name' => 'Bob New',
            'last_name' => 'Jones',
            'email' => 'bobnew@example.com',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);
        $response->assertStatus(403);

        // CANNOT edit
        $response = $this->actingAs($finance)->get(route('students.edit', $student));
        $response->assertStatus(403);

        // CANNOT update
        $response = $this->actingAs($finance)->put(route('students.update', $student), [
            'first_name' => 'Bob Updated',
            'last_name' => 'Jones',
            'email' => 'bob@example.com',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);
        $response->assertStatus(403);

        // CANNOT delete
        $response = $this->actingAs($finance)->delete(route('students.destroy', $student));
        $response->assertStatus(403);
    }

    public function test_hod_cannot_delete_student_with_payments(): void
    {
        $hod = User::factory()->create();
        $hod->assignRole('HOD');

        $programme = Programme::create(['name' => 'Computer Science', 'code' => 'CS']);
        $level = AcademicLevel::create(['name' => 'Level 100', 'numeric_value' => 100]);

        $studentUser = User::factory()->create(['email' => 'student@test.com']);
        $studentUser->assignRole('Student');

        $student = Student::create([
            'user_id' => $studentUser->id,
            'index_number' => 'STU500',
            'first_name' => 'Bob',
            'last_name' => 'Payments',
            'email' => 'student@test.com',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);

        $session = \App\Models\AcademicSession::create(['name' => '2026/2027', 'status' => 'active']);
        $due = \App\Models\Due::create([
            'category_name' => 'COMPSSA Dues',
            'amount' => 100,
            'academic_session_id' => $session->id,
            'academic_level_id' => $level->id,
            'programme_id' => $programme->id,
            'due_date' => now()->addMonth(),
        ]);

        // Create an invoice
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-TEST-001',
            'student_id'     => $student->id,
            'academic_session_id' => $session->id,
            'total_amount'   => 100,
            'paid_amount'    => 100,
            'balance'        => 0,
            'status'         => 'paid',
        ]);

        // Create a payment
        \App\Models\Payment::create([
            'invoice_id'       => $invoice->id,
            'amount'           => 100,
            'payment_date'     => now(),
            'payment_method'   => 'cash',
            'reference_number' => 'TXN123',
            'recorded_by'      => $hod->id,
        ]);

        // Attempt delete
        $response = $this->actingAs($hod)->delete(route('students.destroy', $student));
        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('error', 'Cannot delete student with existing payment records. You can suspend them instead.');

        // Verify student still exists
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
        ]);
    }
}
