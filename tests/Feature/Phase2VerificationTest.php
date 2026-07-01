<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AcademicSession;
use App\Models\Programme;
use App\Models\AcademicLevel;
use App\Models\Student;
use App\Models\Due;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;

class Phase2VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase2_workflow(): void
    {
        // Setup Prerequisite Data
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('HOD');
        
        $session = AcademicSession::create([
            'name' => '2023/2024',
            'is_active' => true,
        ]);

        $programme = Programme::create([
            'name' => 'Computer Science',
            'code' => 'CS',
        ]);

        $level = AcademicLevel::create([
            'name' => 'Level 100',
            'numeric_value' => 100,
        ]);

        $student = Student::create([
            'user_id' => $admin->id,
            'index_number' => '040920150A',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '0241234567',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);

        // 1. Dues Verification: Create a test due
        $this->actingAs($admin)->post(route('dues.store'), [
            'category_name' => 'Departmental Dues 2024',
            'amount' => 100.00,
            'academic_session_id' => $session->id,
            'programme_id' => $programme->id,
            'academic_level_id' => $level->id,
            'due_date' => '2024-12-31',
            'description' => 'Test due for verification',
        ])->assertRedirect(route('dues.index'));

        $this->assertDatabaseHas('dues', [
            'category_name' => 'Departmental Dues 2024',
            'amount' => 100.00,
        ]);

        // 2. Invoices Verification: Generate an invoice
        $this->actingAs($admin)->post(route('invoices.store_generation'), [
            'academic_session_id' => $session->id,
            'programme_id' => $programme->id,
        ])->assertRedirect(route('invoices.index'));

        $invoice = Invoice::where('student_id', $student->id)
                          ->where('academic_session_id', $session->id)
                          ->first();
                          
        $this->assertNotNull($invoice);
        $this->assertEquals(100.00, $invoice->total_amount);
        $this->assertEquals(100.00, $invoice->balance);
        $this->assertEquals('unpaid', $invoice->status);
        $this->assertCount(1, $invoice->items);

        // 3. Payments Verification: Record a partial payment
        $this->actingAs($admin)->post(route('payments.store', $invoice->id), [
            'amount' => 60.00,
            'payment_method' => 'cash',
            'payment_date' => '2024-06-19',
            'notes' => 'Partial payment test',
        ])->assertRedirect(route('invoices.show', $invoice->id));

        $invoice->refresh();
        $this->assertEquals(60.00, $invoice->paid_amount);
        $this->assertEquals(40.00, $invoice->balance);
        $this->assertEquals('partial', $invoice->status);

        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(60.00, $payment->amount);

        // 4. Receipts Verification: PDF Route
        $receipt = Receipt::where('payment_id', $payment->id)->first();
        $this->assertNotNull($receipt);

        $response = $this->actingAs($admin)->get(route('receipts.print', $payment->id));
        $response->assertStatus(200);
    }
}
