<?php

namespace App\Http\Requests\Airdrop;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AirdropGameScoreRequest extends FormRequest
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
            'airdrop_game_id' => [
                'required',
                'exists:airdrop_game_mode,id',
                Rule::unique('airdrop_game_mode_scores')
                    ->where(function ($query) {
                        return $query->where('player_id', $this->user()->id);
                    }),
            ],
            'score' => 'required|integer|min:0',
            'kills' => 'required|integer|min:0',
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
