<?php

namespace App\Http\Requests\API;

use App\Models\Share;
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
        $share = Share::find($this->share_id)->get();

        return [
            'share_id' => 'required|integer|exists:shares,id,user_id,' . auth()->user()->id,
            'type' => [
                'required', 'integer',
                Rule::in([0, 1, 2, 3]),
            ],
            'date' => 'required|date',
            'lot' => 'required|integer',
            'price' => 'required',
            'commission' => 'required',
        ];
    }
}
