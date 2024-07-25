<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Afficher une liste d'utilisateurs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Afficher le formulaire pour créer un nouvel utilisateur.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Cette méthode n'est pas nécessaire pour une API RESTful
    }

    /**
     * Enregistrer un nouvel utilisateur dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $user = User::create([
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user',
            'avatar' => $request->avatar,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Afficher un utilisateur spécifique.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
   
     public function show($id)
    {
        $user = User::findOrFail($id);
        // $this->authorize('view', $user);

        return response()->json($user);
    }

    /**
     * Mettre à jour un utilisateur spécifique dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $currentUser = Auth::user();
        $user = User::findOrFail($id);
        // $user = User::findOrFail($id);
        // $this->authorize('update', $user);

        $request->validate([
            'lastname' => 'string|max:255|nullable',
            'firstname' => 'string|max:255|nullable',
            'email' => 'string|email|max:255|nullable|unique:users,email,' . $id,
            'password' => 'string|min:8|nullable',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->except(['password', 'avatar']));

        return response()->json($user);
    }

    /**
     * Supprimer un utilisateur spécifique de la base de données.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Récupérer tous les utilisateurs (admin seulement).
     *
     * @return \Illuminate\Http\Response
     */
}

