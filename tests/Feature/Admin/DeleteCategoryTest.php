<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteCategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_delete_existing_category()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->json('DELETE', sprintf('/ap/categories/%s', $category->id));

        $response->assertStatus(200);

        $category = Category::first();

        $this->assertNull($category);
    }
}
