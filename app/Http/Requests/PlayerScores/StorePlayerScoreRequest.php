<?php

namespace App\Http\Requests\PlayerScores;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePlayerScoreRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'match_id' => 'required|exists:matches,id',
            'points' => 'required|integer',
            'kills' => 'required|integer',
            'deaths' => 'required|integer',
            'match_unique' => Rule::unique('players_scores', 'player_id')
                ->where(function ($query) {
                    return $query->where('match_id', $this->input('match_id'));
                })
                ->ignore($this->route('players_score')), // Ignorar el registro actual al actualizar
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
