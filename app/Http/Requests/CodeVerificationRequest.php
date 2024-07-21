<?php

namespace App\Http\Requests;

use App\Models\Item;
use App\Models\SpecialCode;
use App\Models\CodeAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CodeVerificationRequest extends FormRequest
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
            'code' => 'required|string',
            'item_id' => 'required|integer|exists:items,code',
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $code = $this->input('code');
            $itemCode = $this->input('item_id');

            $item = Item::where('code', $itemCode)->first();

            if (!$item) {
                $validator->errors()->add('item_id', 'El código del artículo no es válido.');
                return;
            }

            $assignment = CodeAssignment::where('code', $code)
                ->where('item_id', $item->id)
                // ->where('player_id', $user->id)
                ->where('used', false)
                ->first();


            $specialCode = SpecialCode::where('code', $code)
                ->where('item_id', $item->id)
                ->where('purchase_type_id', 2)
                ->first();
            if (!$assignment && !$specialCode) {
                $validator->errors()->add('code', 'El código no existe o no pertenece a este artículo.');
            }

            if ($assignment && $assignment->used) {
                $validator->errors()->add('code', 'El código ya ha sido utilizado.');
            }
        });
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
