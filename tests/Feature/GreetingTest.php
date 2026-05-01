<?php

declare(strict_types=1);

use App\Models\Greeting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
  Storage::fake('public');
});

it('creates a greeting with valid data', function (): void {
  $file = UploadedFile::fake()->image('avatar.jpg');

  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
    'image' => $file,
  ]);

  $response->assertCreated()
    ->assertJsonStructure([
      'data' => [
        'id',
        'name',
        'message',
        'bg_color',
        'image_path',
        'image_url',
        'created_at',
        'updated_at',
      ],
    ])
    ->assertJsonPath('data.name', 'John Doe')
    ->assertJsonPath('data.message', 'Happy Birthday!')
    ->assertJsonPath('data.bg_color', '#FF5733');

  $this->assertDatabaseHas('greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
  ]);
});

it('validates required fields on create', function (): void {
  $response = $this->postJson('/api/greetings', []);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors(['name', 'message', 'bg_color', 'image']);
});

it('validates name max length', function (): void {
  $file = UploadedFile::fake()->image('avatar.jpg');
  $longName = str_repeat('a', 256);

  $response = $this->postJson('/api/greetings', [
    'name' => $longName,
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
    'image' => $file,
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('name');
});

it('validates message max length', function (): void {
  $file = UploadedFile::fake()->image('avatar.jpg');
  $longMessage = str_repeat('a', 1001);

  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => $longMessage,
    'bg_color' => '#FF5733',
    'image' => $file,
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('message');
});

it('validates bg_color hex format', function (): void {
  $file = UploadedFile::fake()->image('avatar.jpg');

  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => 'invalid-color',
    'image' => $file,
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('bg_color');
});

it('validates image is required', function (): void {
  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('image');
});

it('validates image is image file', function (): void {
  $file = UploadedFile::fake()->create('document.pdf', 100);

  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
    'image' => $file,
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('image');
});

it('validates image max size', function (): void {
  $file = UploadedFile::fake()->image('avatar.jpg')->size(6000); // 6MB

  $response = $this->postJson('/api/greetings', [
    'name' => 'John Doe',
    'message' => 'Happy Birthday!',
    'bg_color' => '#FF5733',
    'image' => $file,
  ]);

  $response->assertUnprocessable()
    ->assertJsonValidationErrors('image');
});

it('returns all greetings', function (): void {
  Greeting::factory(3)->create();

  $response = $this->getJson('/api/greetings');

  $response->assertOk()
    ->assertJsonStructure([
      'data' => [
        '*' => [
          'id',
          'name',
          'message',
          'bg_color',
          'image_path',
          'image_url',
          'created_at',
          'updated_at',
        ],
      ],
    ])
    ->assertJsonCount(3, 'data');
});

it('returns a specific greeting', function (): void {
  $greeting = Greeting::factory()->create();

  $response = $this->getJson("/api/greetings/{$greeting->id}");

  $response->assertOk()
    ->assertJsonStructure([
      'data' => [
        'id',
        'name',
        'message',
        'bg_color',
        'image_path',
        'image_url',
        'created_at',
        'updated_at',
      ],
    ])
    ->assertJsonPath('data.id', $greeting->id)
    ->assertJsonPath('data.name', $greeting->name);
});

it('returns 404 for non-existent greeting', function (): void {
  $response = $this->getJson('/api/greetings/99999');

  $response->assertNotFound();
});
