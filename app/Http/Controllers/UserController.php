<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $chargeUsers = User::role('charge')->get();

        // Initialiser une requête pour la table User
        $query = User::query();

        // Si la requête contient un paramètre 'search', on applique la recherche
        if ($request->has('search')) {
            $search = $request->query('search');
            // Appliquer une recherche sur les colonnes 'name' et 'email'
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        // Obtenir les résultats avec les rôles
        $users = $query->with('roles')->get();

        // Vérifier si la requête est AJAX et retourner les données en JSON
        if ($request->ajax()) {
            return response()->json($users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(), // Obtenir les rôles
                ];
            }));
        }

        // Retourner la vue avec les résultats si la requête n'est pas AJAX
        return view('users.index', compact('users', 'chargeUsers'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        // Retournez la vue avec les détails de l'utilisateur
        return view('users.show', compact('user'));
    }

    public function assign_apporteurs(Request $request, $userId)
    {
        $request->validate([
            'apporteurs' => 'required|array',
            'apporteurs.*' => 'exists:users,id',
        ]);

        $charge = User::findOrFail($userId);
        $charge->apporteurs()->sync($request->input('apporteurs'));

        return redirect()->back()->with('success', 'Apporteurs assignés avec succès.');
        // return redirect()->back()->with([
        //     'success' => 'Apporteurs assignés avec succès.',
        //     'charge' => $charge
        // ]);
    }
    public function showRoles(User $user)
    {
        $roles = Role::all(); // Récupère tous les rôles
        return view('users.assign-role', compact('user', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name', // Valide que le rôle existe
        ]);

        $user->syncRoles([$request->role]); // Affecte le rôle
        return redirect()->route('users.index')->with('success', 'Rôle assigné avec succès.');
    }

    public function showAccountManagers()
    {
        $users_charges = User::role('charge')->get();
        $users_apporteurs = User::role('apporteur')->get();
        return view('users.account-managers', compact('users_charges', 'users_apporteurs'));
    }
}
