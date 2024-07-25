<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Opérations pour l'authentification des utilisateurs"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Enregistrer un nouvel utilisateur",
     *     description="Crée un nouvel utilisateur et retourne un token JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="lastname", type="string", description="Nom de famille de l'utilisateur"),
     *                 @OA\Property(property="firstname", type="string", description="Prénom de l'utilisateur"),
     *                 @OA\Property(property="email", type="string", format="email", description="Adresse e-mail de l'utilisateur"),
     *                 @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmation du mot de passe"),
     *                 @OA\Property(property="avatar", type="string", format="binary", description="Avatar de l'utilisateur"),
     *                 required={"lastname", "firstname", "email", "password", "password_confirmation"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Token JWT"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation échouée",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Les données de validation sont invalides."
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user = User::create([
            'lastname' => $validated['lastname'],
            'firstname' => $validated['firstname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar' => $validated['avatar'] ?? null, 
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Connecter un utilisateur",
     *     description="Connecte un utilisateur et retourne un token JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email", description="Adresse e-mail de l'utilisateur"),
     *                 @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur"),
     *                 required={"email", "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentification réussie",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Token JWT"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants invalides",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Identifiants invalides."
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Impossible de créer le token'], 500);
        }

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Déconnecter un utilisateur",
     *     description="Déconnecte l'utilisateur en invalidant le token JWT.",
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Déconnexion réussie"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Échec de la déconnexion",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Échec de la déconnexion, veuillez réessayer."
     *             )
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Déconnexion réussie']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Échec de la déconnexion, veuillez réessayer.'], 500);
        }
    }
}
