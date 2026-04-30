<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\TermsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = User::factory()->create(['role' => 'member']);


        TermsCondition::create(['image' => 'terms.jpg']);
    }

    /** @test */
    public function member_can_see_product_detail_page()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Kamera Canon EOS R',
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($this->member)->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewHas('product');
        $response->assertSee('Kamera Canon EOS R');
    }

    /** @test */
    public function member_gets_404_for_non_existent_product()
    {

        $response = $this->actingAs($this->member)->get(route('products.show', 9999));

        $response->assertStatus(404);
    }
}