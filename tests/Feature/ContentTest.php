<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContentTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function admin_can_see_content_index()
    {
        Content::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)->get(route('contents.index'));

        $response->assertStatus(200);
        $response->assertViewHas('contents');
    }

    /** @test */
    public function admin_can_store_content()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('banner.jpg');

        $response = $this->actingAs($this->admin)->post(route('contents.store'), [
            'title' => 'Promo Camping',
            'image' => $file,
            'redirect_url' => 'https://example.com'
        ]);

        $response->assertRedirect(route('contents.index'));

        $this->assertDatabaseHas('contents', [
            'title' => 'Promo Camping'
        ]);

        Storage::disk('public')->assertExists('contents/' . $file->hashName());
    }

    /** @test */
    public function validation_fails_when_store_content_invalid()
    {
        $response = $this->actingAs($this->admin)->post(route('contents.store'), [
            'title' => '',
            'image' => '',
            'redirect_url' => 'salah-url'
        ]);

        $response->assertSessionHasErrors(['title', 'image', 'redirect_url']);
    }

    /** @test */
    public function admin_can_update_content_without_image()
    {
        Storage::fake('public');

        $content = Content::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('contents.update', $content->id), [
            'title' => 'Update Tanpa Gambar',
            'redirect_url' => 'https://update.com'
        ]);

        $response->assertRedirect(route('contents.index'));

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Update Tanpa Gambar'
        ]);
    }

    /** @test */
    public function admin_can_update_content_with_new_image()
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldFile->store('contents', 'public');

        $content = Content::create([
            'title' => 'Old Content',
            'image' => $oldPath,
            'redirect_url' => 'https://old.com'
        ]);

        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put(route('contents.update', $content->id), [
            'title' => 'Updated Content',
            'image' => $newFile,
            'redirect_url' => 'https://new.com'
        ]);

        $response->assertRedirect(route('contents.index'));

        // file lama harus hilang
        Storage::disk('public')->assertMissing($oldPath);

        // file baru harus ada
        Storage::disk('public')->assertExists('contents/' . $newFile->hashName());
    }

    /** @test */
    public function admin_can_delete_content()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('delete.jpg');
        $path = $file->store('contents', 'public');

        $content = Content::create([
            'title' => 'Hapus Konten',
            'image' => $path,
            'redirect_url' => 'https://hapus.com'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('contents.delete', $content->id));

        $response->assertRedirect(route('contents.index'));

        // file harus hilang
        Storage::disk('public')->assertMissing($path);

        // database harus hilang
        $this->assertDatabaseMissing('contents', [
            'id' => $content->id
        ]);
    }
}