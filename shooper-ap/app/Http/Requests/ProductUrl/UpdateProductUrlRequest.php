<?php

namespace App\Http\Requests\ProductUrl;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductUrlRequest extends FormRequest
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
                'name' => ['required'],
                'price' => ['required'],
                'scratchedPrice' => ['required'],
                'status' => ['required'],
            ];
        } else {
            return [
                'name' => ['sometimes', 'required'],
                'price' => ['sometimes', 'required'],
                'scratchedPrice' => ['sometimes', 'required'],
                'status' => ['sometimes', 'required'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->scratchedPrice) {
            $data['scratched_price'] = $this->scratchedPrice;
        }

        if ($this->status) {
            $data['status_id'] = $this->status;
        }

        $this->merge($data);
    }
}
