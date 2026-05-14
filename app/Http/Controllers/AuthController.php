<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Connexion utilisateur",
     *     description="Permet à un utilisateur de se connecter et recevoir un token JWT",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="user@example.com"
     *             ),
     *
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Connexion réussie"
     *             ),
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="token_type",
     *                     type="string",
     *                     example="bearer"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="expires_in",
     *                     type="integer",
     *                     example=3600
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants incorrects",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Email ou mot de passe incorrect"
     *             ),
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="string",
     *                 nullable=true,
     *                 example=null
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect',
                'data' => null
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
