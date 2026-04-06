<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_see_product_index_with_category()
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    /** @test */
    public function admin_can_store_product()
    {
        $category = Category::factory()->create();

        $data = [
            'category_id'   => $category->id,
            'name'          => 'Tenda Dome Kapasitas 4',
            'description'   => 'Tenda anti badai dan air.',
            'price_per_day' => 50000,
            'stock'         => 10
        ];

        $response = $this->actingAs($this->admin)->post(route('products.store'), $data);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Tenda Dome Kapasitas 4']);
    }

    /** @test */
    public function validation_fails_if_product_price_or_stock_is_negative()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'category_id'   => $category->id,
            'name'          => 'Produk Error',
            'description'   => 'Test',
            'price_per_day' => -100, // Error: Min 0
            'stock'         => -5    // Error: Min 0
        ]);

        $response->assertSessionHasErrors(['price_per_day', 'stock']);
    }

    /** @test */
    public function admin_can_update_product()
    {
        // 1. Persiapan: Buat kategori dan produk yang mau di-update
        $category = Category::factory()->create(['name' => 'Kategori Lama']);
        $newCategory = Category::factory()->create(['name' => 'Kategori Baru']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Produk Sebelum Update',
            'price_per_day' => 10000,
            'stock' => 5
        ]);

        // 2. Data Baru
        $updatedData = [
            'category_id'   => $newCategory->id,
            'name'          => 'Produk Sesudah Update',
            'description'   => 'Deskripsi baru yang lebih detail.',
            'price_per_day' => 25000,
            'stock'         => 15
        ];

        // 3. Eksekusi: Kirim request PUT/PATCH
        $response = $this->actingAs($this->admin)->put(route('products.update', $product->id), $updatedData);

        // 4. Verifikasi
        $response->assertRedirect(route('products.index'));

        // Cek apakah di database data lamanya sudah hilang dan data baru sudah masuk
        $this->assertDatabaseHas('products', [
            'id'            => $product->id,
            'name'          => 'Produk Sesudah Update',
            'category_id'   => $newCategory->id,
            'price_per_day' => 25000
        ]);

        $this->assertDatabaseMissing('products', ['name' => 'Produk Sebelum Update']);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('products.delete', $product->id));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }


    public function test_admin_can_upload_product_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->admin)->post(route('product_images.store'), [
            'product_id' => $product->id,
            'image' => $file
        ]);


        $response->assertSessionHas('success');

        // cek database
        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id
        ]);

        // cek file benar-benar tersimpan
        Storage::disk('public')->assertExists('product-images/' . $file->hashName());
    }

    public function test_upload_fails_if_file_is_not_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $file = UploadedFile::fake()->create('file.pdf', 100);

        $response = $this->actingAs($this->admin)->post(route('product_images.store'), [
            'product_id' => $product->id,
            'image' => $file
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_upload_fails_if_product_not_exist()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->admin)->post(route('product_images.store'), [
            'product_id' => 9999,
            'image' => $file
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_admin_can_delete_product_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $file = UploadedFile::fake()->image('product.jpg');
        $path = $file->store('product-images', 'public');

        $image = ProductImage::create([
            'product_id' => $product->id,
            'image' => $path
        ]);


        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('product_images.delete', $image->id));

        $response->assertSessionHas('success');

        // file harus hilang
        Storage::disk('public')->assertMissing($path);


        $this->assertDatabaseMissing('product_images', [
            'id' => $image->id
        ]);
    }
}