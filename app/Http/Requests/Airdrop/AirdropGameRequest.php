<?php

namespace App\Http\Requests\Airdrop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AirdropGameRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'airdrop_code_id' => 'required|exists:airdrop_codes,code,used,false',
            'room_name' => 'required|string|max:50',
            'map' => 'required|string|max:50',
            'room_time_limit' => 'required|integer|min:1',
            'game_mode_goal' => 'required|integer|min:1',
            'bots' => 'nullable|boolean',   
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 400,
            'message' => 'Comprobar información',
            'errors' => $validator->errors(),
        ], 400));
    }
}
