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
        return [
            'share_id'       => 'required|integer|exists:shares,id,user_id,'.$this->user()->id,
            'type'           => 'required|integer|enum_value:'.TransactionType::class,
            'date_at'        => 'required|date_format:d.m.Y|before_or_equal:'.Carbon::today()->format('d.m.Y'),
            'lot'            => 'required|gt:0',
            'price'          => 'required_if:type,'.TransactionType::Buying.','.TransactionType::Sale,
            'exchange_ratio' => 'required_if:type,'.TransactionType::MergerOut,
            'symbol_id'      => 'integer|required_if:type,'.TransactionType::MergerOut,
            'commission'     => 'required|numeric',
            'dividend_gain'  => 'required_if:type,'.TransactionType::Dividend,
            'preference'     => 'required_if:type,'.TransactionType::Bonus.','.TransactionType::Rights,
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('lot', 'integer', function ($input) {
            return in_array($input->type, [TransactionType::Buying, TransactionType::Sale]);
        });

        $validator->sometimes('lot', 'lte:'.optional(Share::find($this->share_id))->lot, function ($input) {
            return in_array($input->type, [TransactionType::Sale, TransactionType::Dividend, TransactionType::MergerOut]);
        });
    }
}
