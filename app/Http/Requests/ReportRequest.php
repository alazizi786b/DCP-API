<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'brand_name' => 'required|string',
            'model_name' => 'required|string',
            'shop_address' => 'required|string',
            'web_address' => 'required|string',
            'specifications' => 'required|max:255',
            'description' => 'required|max:255',
            'latitude' => 'required|between:-87,90|numeric',
            'longitude' => 'required|between:-180,180|numeric',
        ];
        if (request('reportImage')) {
            $images = count(request('reportImage'));
            foreach (range(0, $images) as $index) {
                $rules['reportImage.' . $index] = 'image|mimes:jpeg,bmp,png|max:2000';
            }
        }
        return $rules;

    }
}
