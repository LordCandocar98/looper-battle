<?php

namespace App\Http\Controllers\Aidrop;

use Illuminate\Support\Str;
use App\Models\AirdropCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AidropCodeController extends Controller
{
    public function index()
    {
        return view('vendor.voyager.airdrop-codes.generate');
    }

    /**
     * Generate Airdrop Codes.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'value' => 'required|numeric|min:0',
        ]);

        $quantity = $request->input('quantity');
        $value = $request->input('value');

        for ($i = 0; $i < $quantity; $i++) {
            AirdropCode::create([
                'code' => Str::upper(Str::random(6)),
                'value' => $value,
            ]);
        }

        return redirect('/admin/airdrop-codes')->with([
            'message' => 'Airdrop codes generated successfully',
            'alert-type' => 'success',
        ]);
        // return response()->json([
        //     'code' => 200,
        //     'message' => 'Airdrop codes generated successfully',
        // ], 200);
    }

    /**
     * Assign Airdrop Code to User.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:airdrop_codes,code',
            'user_id' => 'required|exists:users,id',
        ]);

        $code = $request->input('code');
        $userId = $request->input('user_id');

        $airdropCode = AirdropCode::where('code', $code)->first();
        
        if ($airdropCode->used) {
            return response()->json([
                'code' => 400,
                'message' => 'This code has already been used',
            ], 400);
        }

        $airdropCode->update([
            'user_id' => $userId,
            'used' => true,
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Airdrop code assigned successfully',
        ], 200);
    }
}
