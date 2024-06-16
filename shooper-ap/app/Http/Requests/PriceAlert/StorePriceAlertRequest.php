<?php

namespace App\Http\Requests\PriceAlert;

use Illuminate\Foundation\Http\FormRequest;

class StorePriceAlertRequest extends FormRequest
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
            'price' => ['required'],
            'product' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'desired_price' => $this->price,
            'product_id' => $this->product,
        ]);
    }
}
