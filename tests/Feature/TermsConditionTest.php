<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TermsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TermsConditionTest extends TestCase
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
    public function admin_can_see_terms_index()
    {
        TermsCondition::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)->get(route('terms.index'));

        $response->assertStatus(200);
        $response->assertViewHas('terms');
    }

    /** @test */
    public function admin_can_store_terms_condition()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('terms.jpg');

        $response = $this->actingAs($this->admin)->post(route('terms.store'), [
            'image' => $file
        ]);

        $response->assertRedirect(route('terms.index'));

        $this->assertDatabaseHas('terms_conditions', [
            'image' => 'terms/' . $file->hashName()
        ]);

        Storage::disk('public')->assertExists('terms/' . $file->hashName());
    }

    /** @test */
    public function validation_fails_if_image_not_provided()
    {
        $response = $this->actingAs($this->admin)->post(route('terms.store'), []);

        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function validation_fails_if_file_is_not_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('file.pdf', 100);

        $response = $this->actingAs($this->admin)->post(route('terms.store'), [
            'image' => $file
        ]);

        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function admin_can_update_terms_without_new_image()
    {
        Storage::fake('public');

        $term = TermsCondition::factory()->create([
            'image' => 'terms/old.jpg'
        ]);

        $response = $this->actingAs($this->admin)->put(route('terms.update', $term->id), []);

        $response->assertRedirect(route('terms.index'));

        $this->assertDatabaseHas('terms_conditions', [
            'id' => $term->id
        ]);
    }

    /** @test */
    public function admin_can_update_terms_with_new_image()
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldFile->store('terms', 'public');

        $term = TermsCondition::create([
            'image' => $oldPath
        ]);

        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put(route('terms.update', $term->id), [
            'image' => $newFile
        ]);

        $response->assertRedirect(route('terms.index'));

        // file lama harus hilang
        Storage::disk('public')->assertMissing($oldPath);

        // file baru harus ada
        Storage::disk('public')->assertExists('terms/' . $newFile->hashName());
    }

    /** @test */
    public function admin_can_delete_terms_condition()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('delete.jpg');
        $path = $file->store('terms', 'public');

        $term = TermsCondition::create([
            'image' => $path
        ]);

        $response = $this->actingAs($this->admin)->delete(route('terms.delete', $term->id));

        $response->assertRedirect(route('terms.index'));

        // file harus hilang
        Storage::disk('public')->assertMissing($path);

        // database harus hilang
        $this->assertDatabaseMissing('terms_conditions', [
            'id' => $term->id
        ]);
    }
}