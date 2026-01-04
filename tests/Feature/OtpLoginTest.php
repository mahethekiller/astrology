<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    // We don't use RefreshDatabase here to avoid wiping existing data if not configured correctly, 
    // but in a real test env we should. For this validaiton on existing dev env, I'll be careful.
    // Actually, using RefreshDatabase is standard but might wipe the user's DB if they are using the same DB for testing.
    // I'll check phpunit.xml to see if DB_CONNECTION is sqlite or testing.
    // For now, I'll just use manual cleanup or DatabaseTransactions trait if available.
    // Use RefreshDatabase to migrate the in-memory SQLite database
    use RefreshDatabase;

    public function test_can_send_otp()
    {
        $response = $this->postJson(route('login.send-otp'), [
            'phone_number' => '1234567890',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'otp']);

        $this->assertTrue(Cache::has('otp_1234567890'));
    }

    public function test_can_verify_otp_and_login_existing_user()
    {
        // Create user
        $user = User::factory()->create([
            'phone_number' => '9876543210',
            'email' => 'testval@example.com' // unique email
        ]);

        // Mock OTP in cache
        Cache::put('otp_9876543210', '123456', 600);

        $response = $this->postJson(route('login.verify-otp'), [
            'phone_number' => '9876543210',
            'otp' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_verify_otp_returns_new_user_status_if_not_exists()
    {
        Cache::put('otp_5555555555', '123456', 600);

        $response = $this->postJson(route('login.verify-otp'), [
            'phone_number' => '5555555555',
            'otp' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'new_user']);

        $this->assertGuest();
    }

    public function test_can_register_with_otp()
    {
        // Ensure user doesn't exist
        User::where('phone_number', '1112223333')->delete();

        // Ensure role exists (setup requirement)
        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }

        Cache::put('otp_1112223333', '123456', 600);

        $response = $this->postJson(route('login.register-otp'), [
            'phone_number' => '1112223333',
            'otp' => '123456',
            'name' => 'New User Name'
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('users', [
            'phone_number' => '1112223333',
            'name' => 'New User Name'
        ]);

        $user = User::where('phone_number', '1112223333')->first();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasRole('user'));
    }
}
