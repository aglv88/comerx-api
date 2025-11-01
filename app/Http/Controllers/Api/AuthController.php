<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

/**
 * @tags Autenticação
 */
class AuthController extends Controller
{
    /**
     * Login do usuário
     *
     * Autentica um usuário com username e password, retornando um token JWT.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     * @response 401 {
     *   "error": "Credenciais inválidas"
     * }
     * @response 422 {
     *   "username": ["O campo username é obrigatório."],
     *   "password": ["O campo password é obrigatório."]
     * }
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Obter dados do usuário autenticado
     *
     * Retorna os dados do usuário atualmente autenticado via token JWT.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @authenticated
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "João Silva",
     *   "username": "joao.silva",
     *   "created_at": "2025-11-01T12:00:00.000000Z",
     *   "updated_at": "2025-11-01T12:00:00.000000Z"
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function me()
    {
        return new UserResource(auth('api')->user());
    }

    /**
     * Logout do usuário
     *
     * Invalida o token JWT do usuário autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Logout realizado com sucesso"
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Renovar token JWT
     *
     * Gera um novo token JWT invalidando o anterior.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @authenticated
     *
     * @response 200 {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
