<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_profile_information_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_can_delete_their_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/user', [
                'password' => 'password',
            ]);

        $this->assertNull($user->fresh());
        $response->assertRedirect('/');
    }

    public function test_correct_password_must_be_provided_to_delete_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/profile')
            ->delete('/user', [
                'password' => 'wrong-password',
            ]);

        $this->assertNotNull($user->fresh());
        $response->assertSessionHasErrorsIn('userDeletion', ['password']);
    }

    public function test_password_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertSessionHasErrorsIn('updatePassword', ['current_password']);
    }
} 