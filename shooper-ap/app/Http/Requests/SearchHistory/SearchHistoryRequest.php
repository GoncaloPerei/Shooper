<?php

namespace App\Http\Requests\SearchHistory;

use Illuminate\Foundation\Http\FormRequest;

class SearchHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'search_term' => $this->search,
        ]);
    }
}
