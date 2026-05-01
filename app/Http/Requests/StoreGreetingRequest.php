<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreGreetingRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // No auth required
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, array<int, string>>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'message' => ['required', 'string', 'max:1000'],
      'bg_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
      'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'bg_color.regex' => 'The bg_color must be a valid hex color code (e.g., #FF5733)',
      'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp',
      'image.max' => 'The image may not be greater than 5 MB',
    ];
  }
}
