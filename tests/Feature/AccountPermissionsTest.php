<?php

namespace Tests\Feature;

use App\Models\Enum\Roles;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountPermissionsTest extends TestCase
{
    use RefreshDatabase;

    var $user = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
    }

    //* User has super admin
    public function testUserHasSuperAdmin()
    {
        $this->assertDatabaseHas('users', ['email' => $this->user->email]);
        $this->user->assignRole('superadmin');
        $this->assertTrue($this->user->hasRole('superadmin'));
    }

    //* new user has default role
    public function testNewUserHasDefaultRole()
    {
        $user = UserFactory::new()->make(); 
        $response = $this->post(route('register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $user = User::firstWhere('email', $user->email);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
        $this->assertFalse($user->hasRole(Roles::SUPERADMIN));
        $this->assertTrue($user->hasRole(Roles::DEFAULT));
    }


}
