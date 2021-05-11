<?php

namespace App\Http\Requests\API;

use App\Enums\TransactionType;
use App\Http\Requests\Request;
use App\Models\Share;
use Carbon\Carbon;

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
        if ($this->type == TransactionType::Sale || $this->type == TransactionType::Dividend || $this->type == TransactionType::MergerOut) {
            $addRule .= '|lte:'.optional(Share::find($this->share_id))->lot;
        }

        return [
            'share_id'       => 'required|integer|exists:shares,id,user_id,'.$this->user()->id,
            'type'           => 'required|integer|enum_value:'.TransactionType::class,
            'date_at'        => 'required|date_format:d.m.Y|before_or_equal:'.Carbon::today()->format('d.m.Y'),
            'lot'            => 'required|gt:0'.$addRule,
            'price'          => 'required_if:type,'.TransactionType::Buying.','.TransactionType::Sale,
            'exchange_ratio' => 'required_if:type,'.TransactionType::MergerOut,
            'symbol_id'      => 'integer|required_if:type,'.TransactionType::MergerOut,
            'commission'     => 'required|numeric',
            'dividend_gain'  => 'required_if:type,'.TransactionType::Dividend,
            'preference'     => 'required_if:type,'.TransactionType::Bonus.','.TransactionType::Rights,
        ];
    }
}
