<?php

namespace App\Http\Requests\ProductStatus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductStatusRequest extends FormRequest
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
        $method = $this->method();

        if ($method == "PUT") {
            return [
                'name' => ['required', 'unique:product_statuses',  'regex:/^[a-zA-Z ]+$/'],
                'desc' => ['regex:/^[a-zA-Z ]+$/']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'unique:product_statuses',  'regex:/^[a-zA-Z ]+$/'],
                'desc' => ['sometimes', 'regex:/^[a-zA-Z ]+$/']
            ];
        }
    }
}
