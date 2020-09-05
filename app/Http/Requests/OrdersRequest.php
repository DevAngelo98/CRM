<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class OrdersRequest extends FormRequest
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
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'tags.*' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'The order must be associated with a customer'
        ];
    }
}
