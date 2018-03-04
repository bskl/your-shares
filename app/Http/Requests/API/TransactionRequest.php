<?php

namespace App\Http\Requests\API;

use App\Models\Share;
use App\Enums\TransactionTypes;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
        $share = Share::findOrFail($this->share_id);
        $addRule = ($this->type == TransactionTypes::SALE) ? '|max:' . $share->lot : '';

        return [
            'share_id' => 'required|integer|exists:shares,id,user_id,' . auth()->user()->id,
            'type' => [
                'required', 'integer',
                Rule::in([0, 1, 2, 3]),
            ],
            'date_at' => 'required|date|before_or_equal:' . Carbon::today()->toDateString(),
            'lot' => 'required|numeric|regex:/^[1-9]\d*$/' . $addRule,
            'price' => 'required',
            'commission' => 'required|numeric|min:0',
            'dividend_gain' => 'sometimes|required'
        ];
    }
}
