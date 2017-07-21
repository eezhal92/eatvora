<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function eatvora_admin_can_log_in()
    {
        factory(User::class)->states('admin')->create([
            'email' => 'johndoe@mail.com',
            'password' => bcrypt('password'),
        ]);

        $this->get('/ap/login')->assertStatus(200);

        $response = $this->post('/ap/login', [
             'email' => 'johndoe@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/dashboard');
    }

    /** @test */
    public function regular_users_cannot_log_in()
    {
        factory(User::class)->create([
            'email' => 'jane@mail.com',
            'password' => bcrypt('password'),
        ]);

        $this->get('/ap/login')->assertStatus(200);

        $response = $this->post('/ap/login', [
             'email' => 'johndoe@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/login');
    }
}
