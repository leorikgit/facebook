<?php

namespace Tests\Feature;

use App\User;
use App\UserImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserImagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    use RefreshDatabase;
    /** @test */
    public function user_can_upload_image(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $file = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->post('/api/user-images',[
           'image' => $file,
           'width' => 300,
           'height' => 850,
           'location' => 'cover'
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('user-images/'.$file->hashName());
        $image = UserImage::first();

        $this->assertEquals('user-images/'.$file->hashName(), $image->path);
        $this->assertEquals('300', $image->width);
        $this->assertEquals('850', $image->height);
        $this->assertEquals('cover', $image->location);
        $this->assertEquals($user->id, $image->user_id);

        $response->assertJson([
            'data'=>[
                'type' => 'user_images',
                'user_images_id' => 1,
                'attributes' => [
                    'path' => url($image->path),
                    'location' => $image->location,
                    'width' => $image->width,
                    'height' => $image->height
                ]
            ],
            'links' => [
                'self' => url('users/'. $user->id)
            ]

        ]);

    }
}
