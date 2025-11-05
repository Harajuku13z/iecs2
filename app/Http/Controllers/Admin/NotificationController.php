<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = Notification::with(['sender', 'classe', 'user'])
            ->latest()
            ->paginate(20);
        
        $classes = Classe::with('filiere', 'niveau')->orderBy('nom')->get();
        $etudiants = User::where('role', 'etudiant')->orderBy('name')->get();
        $candidats = User::where('role', 'candidat')->orderBy('name')->get();
        
        return view('admin.notifications.index', compact('notifications', 'classes', 'etudiants', 'candidats'));
    }

    public function create(Request $request): View
    {
        $classes = Classe::with('filiere', 'niveau')->orderBy('nom')->get();
        $etudiants = User::where('role', 'etudiant')->orderBy('name')->get();
        $candidats = User::where('role', 'candidat')->orderBy('name')->get();
        
        return view('admin.notifications.create', compact('classes', 'etudiants', 'candidats'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'contenu' => 'required|string',
                'type' => 'required|in:info,warning,success,danger,message',
                'destinataire_type' => 'required|in:all,classe,user,role',
                'classe_id' => 'required_if:destinataire_type,classe|exists:classes,id',
                'user_id' => 'required_if:destinataire_type,user|exists:users,id',
                'role_name' => 'required_if:destinataire_type,role|in:etudiant,candidat',
                'envoyer_email' => 'nullable',
            ]);

            $user = $request->user();
            $count = 0;

        if ($validated['destinataire_type'] === 'all') {
            // Notifier tous les étudiants
            $etudiants = User::where('role', 'etudiant')->get();
            
            foreach ($etudiants as $etudiant) {
                $notification = Notification::create([
                    'titre' => $validated['titre'],
                    'contenu' => $validated['contenu'],
                    'type' => $validated['type'],
                    'destinataire_type' => 'user',
                    'user_id' => $etudiant->id,
                    'sender_id' => $user->id,
                    'envoye_email' => false,
                ]);
                $count++;
            }
        } elseif ($validated['destinataire_type'] === 'classe') {
            // Notifier une classe
            $classe = Classe::findOrFail($validated['classe_id']);
            $etudiants = $classe->etudiants()->get();
            
            foreach ($etudiants as $etudiant) {
                $notification = Notification::create([
                    'titre' => $validated['titre'],
                    'contenu' => $validated['contenu'],
                    'type' => $validated['type'],
                    'destinataire_type' => 'user',
                    'user_id' => $etudiant->id,
                    'classe_id' => $validated['classe_id'],
                    'sender_id' => $user->id,
                    'envoye_email' => false,
                ]);
                $count++;
            }
        } elseif ($validated['destinataire_type'] === 'user') {
            // Notifier un étudiant spécifique (message)
            $notification = Notification::create([
                'titre' => $validated['titre'],
                'contenu' => $validated['contenu'],
                'type' => 'message',
                'destinataire_type' => 'user',
                'user_id' => $validated['user_id'],
                'sender_id' => $user->id,
                'envoye_email' => $request->has('envoyer_email') && $request->boolean('envoyer_email'),
            ]);
            
            // Envoyer l'email si demandé
            if ($notification->envoye_email) {
                $destinataire = User::findOrFail($validated['user_id']);
                try {
                    Mail::to($destinataire->email)->send(new NotificationMail($notification));
                    $notification->update(['envoye_email' => true]);
                } catch (\Exception $e) {
                    \Log::error('Erreur envoi email notification: ' . $e->getMessage());
                }
            }
            
            $count = 1;
        } elseif ($validated['destinataire_type'] === 'role') {
            // Notifier tous les utilisateurs d'un rôle (etudiant ou candidat)
            $users = User::where('role', $validated['role_name'])->get();
            foreach ($users as $u) {
                $notification = Notification::create([
                    'titre' => $validated['titre'],
                    'contenu' => $validated['contenu'],
                    'type' => $validated['type'],
                    'destinataire_type' => 'user',
                    'user_id' => $u->id,
                    'sender_id' => $user->id,
                    'envoye_email' => $request->has('envoyer_email') && $request->boolean('envoyer_email'),
                ]);
                if ($notification->envoye_email) {
                    try {
                        Mail::to($u->email)->send(new NotificationMail($notification));
                    } catch (\Exception $e) {
                        \Log::error('Erreur envoi email notification (role): ' . $e->getMessage());
                    }
                }
                $count++;
            }
        }
            return redirect()->route('admin.notifications.index')
                ->with('success', "Notification envoyée à $count destinataire(s).");
        } catch (\Throwable $e) {
            \Log::error('[Notifications] store failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
        }
    }

    public function destroy(\App\Models\Notification $notification): RedirectResponse
    {
        $notification->delete();
        return back()->with('success', 'Notification supprimée.');
    }
}
