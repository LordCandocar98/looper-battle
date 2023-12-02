<?php

namespace App\Http\Requests\Matches;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMatchRequest extends FormRequest
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
            'room_name' => 'sometimes|string|max:50',
            'privacy' => 'sometimes|in:public,private',
            'map' => 'sometimes|string|max:50',
            'game_mode' => 'sometimes|in:free_for_all,team_deathmatch,capture_the_flag',
            'max_players' => 'sometimes|integer',
            'room_time_limit' => 'sometimes|integer',
            'game_mode_goal' => 'sometimes|integer',
            'team_selection' => 'sometimes|in:manually,randomly',
            'friendly_fire' => 'sometimes|boolean',
            'bots' => 'sometimes|boolean',
            'pay_tournament' => 'sometimes|boolean',
            'payment_code' => 'sometimes|string',
            'status' => 'required|in:pending,in_progress,finished',
            // 'date' => 'required|date',
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
