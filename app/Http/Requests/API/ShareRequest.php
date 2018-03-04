<?php

namespace App\Http\Requests\API;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ShareRequest extends Request
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
            'portfolio_id' => 'required|integer',
            'symbol_id'    => ['required', 'integer',
                            Rule::unique('shares')->where(function ($query) {
                                return $query->where('portfolio_id', $this->portfolio_id)
                                             ->where('symbol_id', $this->symbol_id);
                            }),
            ],
        ];
    }
}
