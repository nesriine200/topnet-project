<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
class UserController extends Controller
{ use HasRoles;
    public function index(Request $request)
    {
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
            return response()->json($users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames() // Obtenir les rôles
                ];
            }));
        }

        // Retourner la vue avec les résultats si la requête n'est pas AJAX
        return view('auth.index', compact('users'));
    }


    public function show()
    {
        // Vérifiez si l'utilisateur est authentifié
        if (Auth::check()) {
            // Récupérez l'utilisateur connecté
            $user = Auth::user();

            // Retournez la vue avec les détails de l'utilisateur
            return view('auth.show', compact('user'));
        }

        // Redirigez vers la page de connexion si l'utilisateur n'est pas authentifié
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
    }
}
