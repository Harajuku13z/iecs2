<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Vérifier que la candidature est validée
        if (!$candidature || $candidature->statut !== 'admis') {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section. Votre candidature n\'est pas encore validée.');
        }
        
        // Récupérer toutes les notifications pour cet utilisateur
        $notifications = Notification::where('user_id', $user->id)
            ->with('sender', 'classe')
            ->latest()
            ->paginate(20);
        
        return view('etudiant.notifications.index', compact('notifications'));
    }

    public function show(Request $request, Notification $notification): View|RedirectResponse
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Vérifier que la candidature est validée
        if (!$candidature || $candidature->statut !== 'admis') {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section. Votre candidature n\'est pas encore validée.');
        }
        
        // Vérifier que l'utilisateur peut voir cette notification
        if ($notification->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette notification.');
        }
        
        // Marquer comme lue
        if (!$notification->lu) {
            $notification->marquerCommeLu();
        }
        
        return view('etudiant.notifications.show', compact('notification'));
    }

    public function marquerCommeLue(Request $request, Notification $notification)
    {
        $user = $request->user();
        
        // Vérifier que l'utilisateur peut marquer cette notification
        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $notification->marquerCommeLu();
        return response()->json(['success' => true]);
    }
}
