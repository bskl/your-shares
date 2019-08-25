<?php

namespace App\Http\Requests\API;

use App\Enums\TransactionType;
use App\Http\Requests\Request;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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
        $addRule = ($this->type == TransactionType::Sale || $this->type == TransactionType::Dividend) ? '|lte:'.$share->lot : '';
        $addRule .= ($this->type == TransactionType::Buying || $this->type == TransactionType::Sale) ? '|integer' : '';

        return [
            'share_id' => 'required|integer|exists:shares,id,user_id,'.auth()->user()->id,
            'type'     => 'required|integer|enum_value:'.TransactionType::class,
            'date_at'       => 'required|date|before_or_equal:'.Carbon::today()->toDateString(),
            'lot'           => 'required'.$addRule,
            'price'         => 'required',
            'commission'    => 'required|regex:/^0\.\d+$/',
            'dividend_gain' => 'sometimes|required',
        ];
    }
}
