<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Programme;
use App\Models\AcademicLevel;
use Illuminate\Support\Facades\Hash;

class StudentLoginAndResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        \Spatie\Permission\Models\Role::create(['name' => 'HOD']);
        \Spatie\Permission\Models\Role::create(['name' => 'Student']);
    }

    public function test_student_can_login_using_index_number(): void
    {
        $user = User::factory()->create([
            'email' => 'student@test.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('Student');

        $programme = Programme::create(['name' => 'CS', 'code' => 'CS']);
        $level = AcademicLevel::create(['name' => 'L100', 'numeric_value' => 100]);

        Student::create([
            'user_id' => $user->id,
            'index_number' => 'STU999',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'student@test.com',
            'programme_id' => $programme->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);

        // Attempt login using index number
        $response = $this->post(route('login'), [
            'email' => 'STU999',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_must_change_password_user_is_redirected_to_force_change_page(): void
    {
        $user = User::factory()->create([
            'email' => 'student@test.com',
            'must_change_password' => true,
        ]);
        $user->assignRole('Student');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('password.force_change'));
    }

    public function test_must_change_password_user_can_reset_password(): void
    {
        $user = User::factory()->create([
            'email' => 'student@test.com',
            'password' => Hash::make('temp123'),
            'must_change_password' => true,
        ]);
        $user->assignRole('Student');

        $response = $this->actingAs($user)->post(route('password.force_change.store'), [
            'current_password' => 'temp123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
