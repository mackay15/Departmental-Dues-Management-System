<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicSession;
use App\Models\Programme;
use App\Models\AcademicLevel;
use App\Models\Invoice;
use App\Models\PromotionLog;
use App\Models\StudentAcademicRecord;

class Phase3VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_promotion_logic()
    {
        // 1. Setup prerequisite data
        $admin = User::factory()->create();
        $session = AcademicSession::create(['name' => '2024/2025', 'is_active' => true]);
        $programme = Programme::create(['name' => 'Computer Science', 'code' => 'CS']);
        
        $level100 = AcademicLevel::create(['name' => 'Level 100', 'numeric_value' => 100]);
        $level200 = AcademicLevel::create(['name' => 'Level 200', 'numeric_value' => 200]);

        $student1 = Student::create([
            'user_id' => $admin->id,
            'student_number' => 'STU001',
            'index_number' => 'STU001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'student1@example.com',
            'phone' => '0241111111',
            'programme_id' => $programme->id,
            'current_level_id' => $level100->id,
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'user_id' => $admin->id,
            'student_number' => 'STU002',
            'index_number' => 'STU002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'student2@example.com',
            'phone' => '0242222222',
            'programme_id' => $programme->id,
            'current_level_id' => $level100->id,
            'status' => 'active',
        ]);

        // Student 1 has unpaid invoice
        Invoice::create([
            'invoice_number' => 'INV-1111',
            'student_id' => $student1->id,
            'academic_session_id' => $session->id,
            'total_amount' => 100,
            'paid_amount' => 50,
            'balance' => 50,
            'status' => 'partial',
        ]);

        // Student 2 has fully paid invoice (balance 0)
        Invoice::create([
            'invoice_number' => 'INV-2222',
            'student_id' => $student2->id,
            'academic_session_id' => $session->id,
            'total_amount' => 100,
            'paid_amount' => 100,
            'balance' => 0,
            'status' => 'paid',
        ]);

        // 2. Execute Promotion
        $response = $this->actingAs($admin)->post(route('promotions.promote'), [
            'academic_session_id' => $session->id,
            'programme_id' => $programme->id,
            'from_level_id' => $level100->id,
            'to_level_id' => $level200->id,
            'student_ids' => [$student1->id, $student2->id],
        ]);

        $response->assertRedirect(route('promotions.index'));

        // 3. Verify that ONLY Student 2 was promoted
        $student1->refresh();
        $student2->refresh();

        $this->assertEquals($level100->id, $student1->current_level_id); // Not promoted
        $this->assertEquals($level200->id, $student2->current_level_id); // Promoted

        // 4. Verify Academic Record and Logs
        $this->assertDatabaseHas('student_academic_records', [
            'student_id' => $student2->id,
            'academic_session_id' => $session->id,
            'status' => 'promoted',
        ]);

        $this->assertDatabaseMissing('student_academic_records', [
            'student_id' => $student1->id,
        ]);

        $this->assertDatabaseHas('promotion_logs', [
            'academic_session_id' => $session->id,
            'promoted_by' => $admin->id,
        ]);
        
        $log = PromotionLog::first();
        $this->assertCount(1, $log->details); // Only student 2 is inside details
        $this->assertEquals($student2->id, $log->details[0]['student_id']);
    }

    public function test_student_can_pay_invoice()
    {
        // 1. Setup roles, user, student, and invoice
        \Spatie\Permission\Models\Role::create(['name' => 'Student']);
        
        $user = User::factory()->create();
        $user->assignRole('Student');

        $session = AcademicSession::create(['name' => '2024/2025', 'is_active' => true]);
        $programme = Programme::create(['name' => 'Computer Science', 'code' => 'CS']);
        $level100 = AcademicLevel::create(['name' => 'Level 100', 'numeric_value' => 100]);

        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => 'STU999',
            'index_number' => 'STU999',
            'first_name' => 'Alex',
            'last_name' => 'Jones',
            'email' => 'alex@example.com',
            'phone' => '0249999999',
            'programme_id' => $programme->id,
            'current_level_id' => $level100->id,
            'status' => 'active',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-9999',
            'student_id' => $student->id,
            'academic_session_id' => $session->id,
            'total_amount' => 120.00,
            'paid_amount' => 0.00,
            'balance' => 120.00,
            'status' => 'unpaid',
            'due_date' => now()->addDays(30),
        ]);

        // 2. Access the payment page
        $response = $this->actingAs($user)->get(route('student.payments.pay', $invoice));
        $response->assertStatus(200);

        // 3. Process the MoMo payment
        $response = $this->actingAs($user)->post(route('student.payments.process', $invoice), [
            'amount' => 120.00,
            'payment_method' => 'momo',
            'momo_provider' => 'mtn',
            'momo_number' => '0241234567',
        ]);

        $response->assertRedirect(route('invoices.show', $invoice));
        
        // 4. Verify payment database status
        $invoice->refresh();
        $this->assertEquals(120.00, $invoice->paid_amount);
        $this->assertEquals(0.00, $invoice->balance);
        $this->assertEquals('paid', $invoice->status);

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 120.00,
            'payment_method' => 'momo',
            'recorded_by' => $user->id,
        ]);

        $this->assertDatabaseHas('receipts', [
            'issued_by' => null,
        ]);
    }
}
