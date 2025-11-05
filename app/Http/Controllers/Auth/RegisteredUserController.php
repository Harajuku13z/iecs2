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
    public function create(Request $request): View
    {
        // Préserver l'URL prévue si elle existe (pour redirect_to)
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            session(['url.intended' => $redirectTo]);
        }
        
        // Extraire les paramètres de formation depuis l'URL prévue ou la requête
        $formationParams = [
            'filiere_id' => $request->input('filiere_id'),
            'specialite_id' => $request->input('specialite_id'),
            'classe_id' => $request->input('classe_id'),
        ];
        
        // Si l'URL prévue contient des paramètres de formation, les extraire
        $intendedUrl = session()->get('url.intended', $redirectTo);
        if ($intendedUrl && parse_url($intendedUrl, PHP_URL_QUERY)) {
            parse_str(parse_url($intendedUrl, PHP_URL_QUERY), $queryParams);
            if (empty($formationParams['filiere_id']) && isset($queryParams['filiere_id'])) {
                $formationParams['filiere_id'] = $queryParams['filiere_id'];
            }
            if (empty($formationParams['specialite_id']) && isset($queryParams['specialite_id'])) {
                $formationParams['specialite_id'] = $queryParams['specialite_id'];
            }
            if (empty($formationParams['classe_id']) && isset($queryParams['classe_id'])) {
                $formationParams['classe_id'] = $queryParams['classe_id'];
            }
        }
        
        return view('auth.register', compact('formationParams'));
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
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Rediriger selon le rôle de l'utilisateur (par défaut candidat)
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->isEnseignant()) {
            return redirect()->intended(route('enseignant.dashboard'));
        } elseif ($user->isEtudiant() || $user->isCandidat()) {
            // Rediriger les nouveaux candidats vers la création de candidature personnelle (si route publique existe)
            if (\Illuminate\Support\Facades\Route::has('candidature.create')) {
                // Utiliser redirect()->intended() pour préserver l'URL avec les paramètres de formation
                // Si aucune URL prévue, rediriger vers candidature.create
                return redirect()->intended(route('candidature.create'));
            }
            return redirect()->intended(route('etudiant.dashboard'));
        }
        
        // Par défaut, rediriger vers la page d'accueil
        return redirect()->intended('/');
    }
}
