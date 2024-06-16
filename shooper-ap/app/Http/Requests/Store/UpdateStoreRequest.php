<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
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
                'name' => ['required', 'unique:stores'],
                'website' => ['required', 'regex:/^(http(s):\/\/.)[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/'],
                'titleTag' => ['required'],
                'priceTag' => ['required'],
                'scratchedPriceTag' => ['required'],
                'deliveryEstimateTag' => ['required'],
                'status' => ['required'],
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'unique:stores'],
                'website' => ['sometimes', 'regex:/^(http(s):\/\/.)[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/'],
                'titleTag' => ['sometimes'],
                'priceTag' => ['sometimes'],
                'scratchedPriceTag' => ['sometimes', 'required'],
                'deliveryEstimateTag' => ['sometimes'],
                'status' => ['sometimes', 'required'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->website) {
            $data['website_url'] = $this->website;
        }

        if ($this->titleTag) {
            $data['title_tag'] = $this->titleTag;
        }

        if ($this->priceTag) {
            $data['price_tag'] = $this->priceTag;
        }

        if ($this->scratchedPriceTag) {
            $data['scratched_price_tag'] = $this->scratchedPriceTag;
        }

        if ($this->deliveryEstimateTag) {
            $data['delivery_estimate_tag'] = $this->deliveryEstimateTag;
        }

        if ($this->status) {
            $data['status_id'] = $this->status;
        }

        $this->merge($data);
    }
}
