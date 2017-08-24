<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditCategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_edit_form()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->get(sprintf('/ap/categories/%s/edit', $category->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function can_update_existing_category()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->patch(sprintf('/ap/categories/%s', $category->id), [
            'name' => 'Spicy',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');

        $category = Category::first();

        $this->assertEquals('Spicy', $category->name);
        $this->assertEquals('spicy', $category->slug);
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create([
            'name' => 'Spicy',
            'slug' => 'spicy',
        ]);

        $response = $this->actingAs($admin)
            ->from('/ap/categories')
            ->patch(sprintf('/ap/categories/%s', $category->id), [
                'name' => '',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');
        $response->assertSessionHasErrors('name');

        $category->refresh();

        $this->assertEquals('Spicy', $category->name);
        $this->assertEquals('spicy', $category->slug);
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create([
            'name' => 'Spicy',
            'slug' => 'spicy',
        ]);

        $response = $this->actingAs($admin)
            ->from('/ap/categories')
            ->patch(sprintf('/ap/categories/%s', $category->id), [
                'name' => 'Mo',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/categories');
        $response->assertSessionHasErrors('name');

        $category->refresh();

        $this->assertEquals('Spicy', $category->name);
        $this->assertEquals('spicy', $category->slug);
    }
}
