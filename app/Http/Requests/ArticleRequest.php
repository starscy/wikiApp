<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Статьи
 */
class ArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'url' => 'required|string|max:500',
            'text' => 'required|string',
            'words' => 'required|array',
            'size' => 'required|numeric',
            'words_count' => 'required|integer',
        ];
    }
}
