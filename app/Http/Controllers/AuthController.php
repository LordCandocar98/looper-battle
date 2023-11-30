<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->get('name'),
                'nickname' => $request->get('nickname'),
                'birthday_date' => $request->get('birthday_date'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'role_id' => 2,
                'is_verified' => false,
            ]);
            $user->notify(new VerifyEmailNotification($user));
            DB::commit();
            return response()->json([
                'code' => 201,
                'user' => $user,
                'message' => 'Usuario registrado exitosamente.  Se enviará un correo electrónico para verificar.'
            ], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al registrar el usuario.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('password');
        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->input('email');
        } else {
            $credentials['nickname'] = $request->input('email');
        }
        if (!$token = JWTAuth::attempt($credentials)) {
            // Autenticación fallida
            throw new HttpResponseException(response()->json([
                'code' => 401,
                'message' => 'Credenciales inválidas.'
            ], 401));
        }
        // Verificar si el usuario está verificado
        $user = auth()->user();
        if (!$user->is_verified) {
            throw new HttpResponseException(response()->json([
                'code' => 401,
                'message' => 'El correo no ha sido verificado.'
            ], 401));
        }

        return $this->respondWithToken($token);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud exitosa',
            'data' => auth()->user(),
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken(), true);

        auth()->logout();

        return response()->json(['message' => 'Se ha cerrado sesión.']);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'code' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'data' => auth()->user()
        ]);
    }

    public function verify($id)
    {
        $user = User::find($id);
        if (!$user) {
        }
        $user->markEmailAsVerified();
        $user->update(['is_verified' => true]);
        return redirect('/')->with('verified', true);
    }
}
