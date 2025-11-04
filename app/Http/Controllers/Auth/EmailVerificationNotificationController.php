<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
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

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
