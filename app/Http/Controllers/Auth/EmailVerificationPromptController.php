<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($user->isEnseignant()) {
                return redirect()->intended(route('enseignant.dashboard', absolute: false));
            } elseif ($user->isEtudiant() || $user->isCandidat()) {
                return redirect()->intended(route('etudiant.dashboard', absolute: false));
            }
            return redirect()->intended('/', absolute: false);
        }
        
        return view('auth.verify-email');
    }
}
