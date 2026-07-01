<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $user->assignRole('HOD');

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('HOD');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_non_hod_cannot_update_profile_information(): void
    {
        foreach (['Finance Officer', 'Auditor', 'Student'] as $role) {
            $user = User::factory()->create();
            $user->assignRole($role);

            $response = $this
                ->actingAs($user)
                ->patch('/profile', [
                    'name' => 'Hacked Name',
                    'email' => 'hacked@example.com',
                ]);

            $response->assertStatus(403);
        }
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();
        $user->assignRole('HOD');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();
        $user->assignRole('HOD');

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_non_hod_cannot_delete_account(): void
    {
        foreach (['Finance Officer', 'Auditor', 'Student'] as $role) {
            $user = User::factory()->create();
            $user->assignRole($role);

            $response = $this
                ->actingAs($user)
                ->delete('/profile', [
                    'password' => 'password',
                ]);

            $response->assertStatus(403);
        }
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();
        $user->assignRole('HOD');

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}

