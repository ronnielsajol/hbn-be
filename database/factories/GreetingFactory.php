<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Greeting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Greeting>
 */
final class GreetingFactory extends Factory
{
  protected $model = Greeting::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => $this->faker->name(),
      'message' => $this->faker->sentence(10),
      'bg_color' => $this->faker->hexColor(),
      'image_path' => 'greetings/' . $this->faker->uuid() . '.jpg',
    ];
  }
}
