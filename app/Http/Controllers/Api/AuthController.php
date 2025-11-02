<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * @tags Autenticação
 */
class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Login do usuário
     *
     * Autentica um usuário com username e password, retornando um token JWT.
     *
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
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->unauthorized('Invalid credentials');
        }

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Obter dados do usuário autenticado
     *
     * Retorna os dados do usuário atualmente autenticado via token JWT.
     *
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
    public function me(): JsonResponse
    {
        return $this->success(new UserResource(auth('api')->user()));
    }

    /**
     * Logout do usuário
     *
     * Invalida o token JWT do usuário autenticado.
     *
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
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return $this->successWithMessage('Successfully logged out');
    }

    /**
     * Renovar token JWT
     *
     * Gera um novo token JWT invalidando o anterior.
     *
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
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
