<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        \Spatie\Permission\Models\Role::create(['name' => 'HOD']);
        \Spatie\Permission\Models\Role::create(['name' => 'Finance Officer']);
        \Spatie\Permission\Models\Role::create(['name' => 'Auditor']);
        \Spatie\Permission\Models\Role::create(['name' => 'Student']);
    }

    public function test_hod_sees_hod_dashboard(): void
    {
        $hod = User::factory()->create();
        $hod->assignRole('HOD');

        $response = $this->actingAs($hod)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('HOD Dashboard');
        $response->assertDontSee('Finance Dashboard');
    }

    public function test_finance_officer_sees_finance_dashboard(): void
    {
        $finance = User::factory()->create();
        $finance->assignRole('Finance Officer');

        $response = $this->actingAs($finance)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Finance Dashboard');
        $response->assertDontSee('HOD Dashboard');
    }

    public function test_auditor_sees_auditor_dashboard(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        $response = $this->actingAs($auditor)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Auditor Dashboard');
        $response->assertDontSee('HOD Dashboard');
    }
}
