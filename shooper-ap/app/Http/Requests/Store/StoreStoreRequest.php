<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'name' => ['required', 'unique:stores'],
            'website' => ['required'],
            'titleTag' => ['required'],
            'priceTag' => ['required'],
            'scratchedPriceTag' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'website_url' => $this->website,
            'title_tag' => $this->titleTag,
            'price_tag' => $this->priceTag,
            'scratched_price_tag' => $this->scratchedPriceTag,
        ]);
    }
}
