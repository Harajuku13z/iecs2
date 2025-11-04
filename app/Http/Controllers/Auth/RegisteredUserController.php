<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Rediriger selon le rôle de l'utilisateur (par défaut candidat)
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect(route('admin.dashboard', absolute: false));
        } elseif ($user->isEnseignant()) {
            return redirect(route('enseignant.dashboard', absolute: false));
        } elseif ($user->isEtudiant() || $user->isCandidat()) {
            return redirect(route('etudiant.dashboard', absolute: false));
        }
        
        // Par défaut, rediriger vers la page d'accueil
        return redirect('/');
    }
}
