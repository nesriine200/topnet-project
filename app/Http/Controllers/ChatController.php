<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        $users = collect([]);

        // Check if the user has the 'charge' role
        if ($user->hasRole('charge')) {
            // If the user is a "charge", get all the "apporteurs" related to this "charge"
            $users = $user->apporteurs;
        } elseif ($user->hasRole('apporteur')) {
            // If the user is an "apporteur", get all the "charges" related to this "apporteur"
            $users = $user->charges;
        } else {
            $users = User::all();
        }

        return view('chat.index', compact('users'));
    }

    public function show($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Initialize the users collection
        $users = collect([]);

        // Check if the user has the 'charge' role
        if ($user->hasRole('charge')) {
            // If the user is a "charge", get all the "apporteurs" related to this "charge"
            $users = $user->apporteurs;
            // Find the specific user to chat with (the one with the passed ID)
            $user = $users->find($id);
        } elseif ($user->hasRole('apporteur')) {
            // If the user is an "apporteur", get all the "charges" related to this "apporteur"
            $users = $user->charges;
            // Find the specific user to chat with (the one with the passed ID)
            $user = $users->find($id);
        }

        // If the user to chat with is not found or doesn't exist in the user's relation
        if (!$user) {
            return redirect()->route('chat.index')->with('error', 'User not found or not authorized to chat.');
        }

        // Return the chat view with the selected user
        return view('chat.show', compact('user', 'users'));
    }
}
