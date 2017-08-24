<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateCategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function admin_can_view_create_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/categories');

        $response->assertStatus(200);
    }

    /** @test */
    function can_create_category()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('/ap/categories', [
            'name' => 'Diet',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');

        $this->assertCount(1, Category::all());
        $this->assertEquals('Diet', Category::first()->name);
        $this->assertEquals('diet', Category::first()->slug);
    }

    /** @test */
    function name_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/categories')
            ->post('/ap/categories', [
                'name' => '',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');
        $response->assertSessionHasErrors('name');

        $this->assertCount(0, Category::all());
    }

    /** @test */
    function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/categories')
            ->post('/ap/categories', [
                'name' => 'Am',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');
        $response->assertSessionHasErrors('name');

        $this->assertCount(0, Category::all());
    }
}
