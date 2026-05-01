<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Greeting extends Model
{
  /** @use HasFactory<\Database\Factories\GreetingFactory> */
  use HasFactory;

  protected $fillable = [
    'name',
    'message',
    'bg_color',
    'image_path',
  ];
}
