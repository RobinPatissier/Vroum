<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Afficher le formulaire pour modifier un utilisateur spécifique.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Cette méthode n'est pas nécessaire pour une API RESTful
    }

    /**
     * Mettre à jour un utilisateur spécifique dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'lastname' => 'sometimes|required|string|max:255',
            'firstname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $user->update($request->only(['lastname', 'firstname', 'email', 'role', 'avatar']) + [
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json($user);
    }

    /**
     * Supprimer un utilisateur spécifique de la base de données.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}

