<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
final class AnalyzeRecipeRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'photo.required' => 'Lütfen bir yemek fotoğrafı seçin.',
            'photo.image' => 'Yüklenen dosya bir görsel olmalıdır.',
            'photo.mimes' => 'Desteklenen formatlar: JPEG, PNG, WebP.',
            'photo.max' => 'Fotoğraf boyutu en fazla 8MB olabilir.',
        ];
    }
}
