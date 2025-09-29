<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $bookId = $this->route('book') ? ($this->route('book')->id ?? $this->route('book')) : null;

        return [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => ['sometimes','required','string','max:255', Rule::unique('books','isbn')->ignore($bookId)],
            'pages' => 'nullable|integer|min:1',
            'published_at' => 'nullable|date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422));
    }
}
