<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name'         => 'Test User',
                'email'        => 'test@example.com',
                'organization' => 'SRE Telkom University',
                'position'     => 'Ketua Pelaksana',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('SRE Telkom University', $user->organization);
        $this->assertSame('Ketua Pelaksana', $user->position);
    }

    public function test_email_must_be_valid_when_updating_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name'  => 'Test User',
                'email' => 'bukan-email',
            ]);

        $response
            ->assertSessionHasErrors('email')
            ->assertRedirect('/profile');
    }

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/profile/password', [
                'current_password'      => 'password',
                'password'              => 'password-baru-1',
                'password_confirmation' => 'password-baru-1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('password-baru-1', $user->refresh()->password));
    }

    public function test_correct_current_password_must_be_provided_to_update_password(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/profile/password', [
                'current_password'      => 'password-salah',
                'password'              => 'password-baru-1',
                'password_confirmation' => 'password-baru-1',
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirect('/profile');

        // Password lama tidak berubah.
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }
}
