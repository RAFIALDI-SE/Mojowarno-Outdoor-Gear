<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_see_category_index()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    /** @test */
    public function admin_can_store_category()
    {
        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'name' => 'Alat Camping'
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Alat Camping']);
    }

    /** @test */
    public function admin_can_update_category()
    {
        $category = Category::factory()->create(['name' => 'Lama']);

        $response = $this->actingAs($this->admin)->put(route('categories.update', $category->id), [
            'name' => 'Baru'
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Baru']);
    }

    /** @test */
    public function admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('categories.delete', $category->id));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}