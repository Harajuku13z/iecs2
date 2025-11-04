<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return $this->redirectToDashboard($user, '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirectToDashboard($user, '?verified=1');
    }
    
    private function redirectToDashboard($user, $query = ''): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false) . $query);
        } elseif ($user->isEnseignant()) {
            return redirect()->intended(route('enseignant.dashboard', absolute: false) . $query);
        } elseif ($user->isEtudiant() || $user->isCandidat()) {
            return redirect()->intended(route('etudiant.dashboard', absolute: false) . $query);
        }
        return redirect()->intended('/' . $query);
    }
}
