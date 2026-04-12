<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberProductKatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user member buat login
        $this->member = User::factory()->create(['role' => 'member']);
    }

    /** @test */
    public function member_can_see_all_products_in_catalog()
    {
        Category::factory()->create();
        Product::factory()->count(15)->create(); // Buat 15 produk

        $response = $this->actingAs($this->member)->get(route('products.all'));

        $response->assertStatus(200);
        $response->assertViewIs('member.product.all');
        $response->assertViewHas('products');

        // Cek apakah pagination jalan (kodinganmu paginate 12)
        $products = $response->viewData('products');
        $this->assertCount(12, $products);
    }

    /** @test */
    public function member_can_search_product_by_name()
    {
        $cat = Category::factory()->create();
        Product::factory()->create(['name' => 'Tenda Dome', 'category_id' => $cat->id]);
        Product::factory()->create(['name' => 'Carrier 60L', 'category_id' => $cat->id]);

        // Cari "Tenda"
        $response = $this->actingAs($this->member)->get(route('products.all', ['search' => 'Tenda']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('products'));
        $this->assertEquals('Tenda Dome', $response->viewData('products')->first()->name);
    }

    /** @test */
    public function member_can_filter_product_by_category()
    {
        $category1 = Category::factory()->create(['name' => 'Alat Camping']);
        $category2 = Category::factory()->create(['name' => 'Alat Selam']);

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        // Filter kategori "Alat Camping"
        $response = $this->actingAs($this->member)->get(route('products.all', ['category' => $category1->id]));

        $response->assertStatus(200);
        $this->assertCount(3, $response->viewData('products'));
    }
}