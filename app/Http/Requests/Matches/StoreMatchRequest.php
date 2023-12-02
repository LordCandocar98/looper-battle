<?php

namespace App\Http\Requests\Matches;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMatchRequest extends FormRequest
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
            'room_name' => 'required|string|max:50',
            'privacy' => 'required|in:public,private',
            'map' => 'required|string|max:50',
            'game_mode' => 'required|in:free_for_all,team_deathmatch,capture_the_flag',
            'max_players' => 'required|integer',
            'room_time_limit' => 'required|integer',
            'game_mode_goal' => 'required|integer',
            'team_selection' => 'required|in:manually,randomly',
            'friendly_fire' => 'required|boolean',
            'bots' => 'required|boolean',
            'pay_tournament' => 'required|boolean',
            'payment_code' => 'nullable|string', // Ajusta según tus requisitos
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
