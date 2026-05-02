<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Slider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SliderCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /** @test */
    public function admin_can_see_slider_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Slider::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('sliders.index'));

        $response->assertStatus(200);
        $response->assertViewHas('sliders');
    }

    /** @test */
    public function admin_can_store_new_slider_with_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);


        $file = UploadedFile::fake()->image('promo-lebaran.jpg');

        $response = $this->actingAs($admin)->post(route('sliders.store'), [
            'title' => 'Promo Lebaran',
            'subtitle' => 'Diskon sewa 50%',
            'image' => $file
        ]);

        $response->assertRedirect(route('sliders.index'));
        $this->assertDatabaseHas('sliders', ['title' => 'Promo Lebaran']);


        $slider = Slider::first();
        Storage::disk('public')->assertExists($slider->image);
    }

    /** @test */
    public function admin_can_update_slider_and_replace_old_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);


        $oldFile = 'sliders/old-banner.jpg';
        Storage::disk('public')->put($oldFile, 'fake content');
        $slider = Slider::factory()->create(['image' => $oldFile]);


        $newFile = UploadedFile::fake()->image('new-banner.jpg');

        $response = $this->actingAs($admin)->put(route('sliders.update', $slider->id), [
            'title' => 'Updated Title',
            'image' => $newFile
        ]);

        $response->assertRedirect(route('sliders.index'));


        Storage::disk('public')->assertMissing($oldFile);


        $slider->refresh();
        Storage::disk('public')->assertExists($slider->image);
    }

    /** @test */
    public function admin_can_delete_slider_and_remove_file()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $filePath = 'sliders/to-delete.jpg';
        Storage::disk('public')->put($filePath, 'fake content');
        $slider = Slider::factory()->create(['image' => $filePath]);

        $response = $this->actingAs($admin)->delete(route('sliders.destroy', $slider->id));

        $response->assertRedirect(route('sliders.index'));
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);


        Storage::disk('public')->assertMissing($filePath);
    }
}