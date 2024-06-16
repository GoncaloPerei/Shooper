<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
                'name' => ['required', 'unique:products'],
                'status' => ['required', Rule::in(['1', '2', '3'])],
                'category' => ['required'],
                'brand' => ['required'],
                'filename' => ['required']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'unique:products'],
                'status' => ['sometimes', Rule::in(['1', '2', '3'])],
                'category' => ['sometimes', 'required'],
                'brand' => ['sometimes', 'required'],
                'filename' => ['sometimes', 'required']
            ];
        }
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->status) {
            $data['status_id'] = $this->status;
        }

        if ($this->category) {
            $data['product_category_id'] = $this->category;
        }

        if ($this->brand) {
            $data['product_brand_id'] = $this->brand;
        }

        $this->merge($data);
    }
}
