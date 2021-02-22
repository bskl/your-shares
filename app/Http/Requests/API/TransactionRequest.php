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
        $addRule = '';
        if ($this->type == TransactionType::Buying || $this->type == TransactionType::Sale) {
            $addRule .= '|integer';
        }
        if ($this->type == TransactionType::Sale || $this->type == TransactionType::Dividend || $this->type == TransactionType::Merger) {
            $addRule .= '|lte:'.optional(Share::find($this->share_id))->lot;
        }

        return [
            'share_id'      => 'required|integer|exists:shares,id,user_id,'.$this->user()->id,
            'type'          => 'required|integer|enum_value:'.TransactionType::class,
            'date_at'       => 'required|date|before_or_equal:'.Carbon::today()->toDateString(),
            'lot'           => 'required|gt:0'.$addRule,
            'price'         =>
                Rule::requiredIf(function () {
                    return ($this->type == TransactionType::Buying) || ($this->type == TransactionType::Sale);
                }),
            'commission'    => 'required|numeric',
            'dividend_gain' => 'required_if:type,'.TransactionType::Dividend,
        ];
    }
}
