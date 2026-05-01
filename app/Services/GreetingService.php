<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Greeting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GreetingService
{
  /**
   * Create a new greeting with image upload
   */
  public function createGreeting(
    string $name,
    string $message,
    string $bgColor,
    UploadedFile $image,
  ): Greeting {
    // Store the image
    $imagePath = $image->store('greetings', 'public');

    // Create greeting record
    return Greeting::create([
      'name' => $name,
      'message' => $message,
      'bg_color' => $bgColor,
      'image_path' => $imagePath,
    ]);
  }

  /**
   * Get all greetings
   */
  public function getAllGreetings(): Collection
  {
    return Greeting::all();
  }

  /**
   * Get a specific greeting by ID
   *
   * @throws NotFoundHttpException
   */
  public function getGreeting(int $id): Greeting
  {
    $greeting = Greeting::find($id);

    if (!$greeting) {
      throw new NotFoundHttpException("Greeting with ID {$id} not found");
    }

    return $greeting;
  }
}
