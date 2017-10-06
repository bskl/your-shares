<?php

namespace App\Http\Requests\API;

use App\Http\Requests\Request;

class TransactionRequest extends Request
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
            'portfolio_symbol_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'share' => 'required',
            'price' => 'required',
            'commission' => 'required',
        ];
    }
}
