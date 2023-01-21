<?php

namespace App\Http\Requests\API;

use App\Enums\Currency;
use App\Http\Requests\Request;
use Illuminate\Validation\Rules\Enum;

class PortfolioRequest extends Request
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
            'name' => 'sometimes|required|string',
            'currency' => ['sometimes', 'required', new Enum(Currency::class)],
            'commission' => 'sometimes|required|numeric',
            'filtered' => 'sometimes|boolean',
        ];
    }
}
