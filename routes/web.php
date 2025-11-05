<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\FiliereController;
use App\Http\Controllers\Admin\NiveauController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CandidatureController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActualiteController;
use App\Http\Controllers\Admin\EvenementController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Public\FormationController;
use App\Http\Controllers\Public\CandidatureController as PublicCandidatureController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/formations', [FormationController::class, 'index'])->name('formations');
Route::get('/formations/{filiere}', [FormationController::class, 'show'])->name('formations.show');

Route::get('/evenements', [\App\Http\Controllers\Public\EvenementController::class, 'index'])->name('evenements');
Route::get('/evenements/{evenement}', [\App\Http\Controllers\Public\EvenementController::class, 'show'])->name('evenements.show');

Route::get('/actualites', [\App\Http\Controllers\Public\ActualiteController::class, 'index'])->name('actualites');
Route::get('/actualites/{actualite}', [\App\Http\Controllers\Public\ActualiteController::class, 'show'])->name('actualites.show');

Route::get('/admission', [\App\Http\Controllers\Public\AdmissionController::class, 'index'])->name('admission');
Route::get('/admission/download-pdf', [\App\Http\Controllers\Public\AdmissionController::class, 'downloadPdf'])->name('admission.download-pdf');

Route::post('/admission', function () {
    // Rediriger vers la création de candidature si l'utilisateur est connecté
    if (auth()->check()) {
        return redirect()->route('candidature.create');
    }
    // Sinon rediriger vers la page d'admission
    return redirect()->route('admission');
})->name('admission.submit');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    Route::resource('filieres', FiliereController::class);
    Route::resource('niveaux', NiveauController::class);
    Route::resource('classes', ClasseController::class)
        ->parameters(['classes' => 'classe']);
    Route::get('/cours/classe/{classe}', [CoursController::class, 'showClasse'])->name('cours.classe.show');
    Route::post('/cours/{cour}/detach', [CoursController::class, 'detachFromClasse'])->name('cours.detach');
    Route::resource('cours', CoursController::class);
    Route::resource('users', UserController::class);
    
    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
    Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{candidature}/edit', [CandidatureController::class, 'edit'])->name('candidatures.edit');
    Route::patch('/candidatures/{candidature}', [CandidatureController::class, 'update'])->name('candidatures.update');
    Route::patch('/candidatures/{candidature}/status', [CandidatureController::class, 'updateStatus'])->name('candidatures.updateStatus');
    Route::patch('/candidatures/{candidature}/doc-status', [CandidatureController::class, 'updateDocumentStatus'])->name('candidatures.updateDocStatus');
    Route::patch('/candidatures/{candidature}/schedule', [CandidatureController::class, 'schedule'])->name('candidatures.schedule');
    Route::patch('/candidatures/{candidature}/remind', [CandidatureController::class, 'remind'])->name('candidatures.remind');
    Route::patch('/candidatures/{candidature}/assign-class', [CandidatureController::class, 'assignClass'])->name('candidatures.assignClass');
    Route::patch('/candidatures/{candidature}/mark-evaluated', [CandidatureController::class, 'markEvaluated'])->name('candidatures.markEvaluated');
    Route::patch('/candidatures/{candidature}/mark-inscription-paid', [CandidatureController::class, 'markInscriptionPaid'])->name('candidatures.markInscriptionPaid');
    Route::delete('/candidatures/{candidature}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test-email');
    
    Route::get('/home-content', [HomeContentController::class, 'index'])->name('home-content.index');
    Route::post('/home-content', [HomeContentController::class, 'update'])->name('home-content.update');
    
    Route::get('/admission-content', [\App\Http\Controllers\Admin\AdmissionContentController::class, 'index'])->name('admission-content.index');
    Route::post('/admission-content', [\App\Http\Controllers\Admin\AdmissionContentController::class, 'update'])->name('admission-content.update');
    
    Route::get('/event-content', [\App\Http\Controllers\Admin\EventContentController::class, 'index'])->name('event-content.index');
    Route::post('/event-content', [\App\Http\Controllers\Admin\EventContentController::class, 'update'])->name('event-content.update');
    
    Route::get('/actualite-content', [\App\Http\Controllers\Admin\ActualiteContentController::class, 'index'])->name('actualite-content.index');
    Route::post('/actualite-content', [\App\Http\Controllers\Admin\ActualiteContentController::class, 'update'])->name('actualite-content.update');
    
    Route::resource('actualites', ActualiteController::class);
    Route::resource('evenements', EvenementController::class);
    
    // Routes personnalisées pour calendrier-cours (AVANT le resource pour éviter les conflits)
    Route::get('/calendrier-cours/classe/{classe}', [\App\Http\Controllers\Admin\CalendrierCoursController::class, 'show'])->name('calendrier-cours.show');
    Route::get('/calendrier-cours/classe/{classe}/semestre/{semestre}', [\App\Http\Controllers\Admin\CalendrierCoursController::class, 'showSemestre'])->name('calendrier-cours.show-semestre');
    // Route de redirection pour les anciennes URLs (compatibilité) - doit être avant le resource
    Route::get('/calendrier-cours/{id}', function($id) {
        // Vérifier si c'est une classe
        $classe = \App\Models\Classe::find($id);
        if ($classe) {
            return redirect()->route('admin.calendrier-cours.show', $classe);
        }
        // Sinon, vérifier si c'est un calendrier_cours
        $calendrier = \App\Models\CalendrierCours::find($id);
        if ($calendrier) {
            return redirect()->route('admin.calendrier-cours.edit', $calendrier);
        }
        abort(404, 'Ressource non trouvée');
    })->where('id', '[0-9]+');
    
    Route::resource('calendrier-cours', \App\Http\Controllers\Admin\CalendrierCoursController::class)->except(['show']);
    // AJAX calendrier cours
    Route::get('/classes/{classe}/cours-json', [\App\Http\Controllers\Admin\CalendrierCoursController::class, 'getCoursByClasse'])->name('classes.cours.json');
    Route::get('/cours/{cours}/enseignants-json', [\App\Http\Controllers\Admin\CalendrierCoursController::class, 'getEnseignantsByCours'])->name('cours.enseignants.json');
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
    
    // Gestion des enseignants
    Route::get('/enseignants', [\App\Http\Controllers\Admin\EnseignantController::class, 'index'])->name('enseignants.index');
    Route::get('/enseignants/{enseignant}', [\App\Http\Controllers\Admin\EnseignantController::class, 'show'])->name('enseignants.show');
});

// Enseignant Routes
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Enseignant\DashboardController::class, 'index'])->name('dashboard');
    
    // Cours
    Route::get('/cours', [\App\Http\Controllers\Enseignant\CoursController::class, 'index'])->name('cours.index');
    Route::get('/cours/{cours}', [\App\Http\Controllers\Enseignant\CoursController::class, 'show'])->name('cours.show');
    Route::get('/cours/{cours}/classes', [\App\Http\Controllers\Enseignant\CoursController::class, 'getClasses'])->name('cours.classes');
    
    // Classes
    Route::get('/classes', [\App\Http\Controllers\Enseignant\ClasseController::class, 'index'])->name('classes.index');
    Route::get('/classes/{classe}', [\App\Http\Controllers\Enseignant\ClasseController::class, 'show'])->name('classes.show');
    Route::get('/classes/{classe}/etudiants', [\App\Http\Controllers\Enseignant\ClasseController::class, 'getEtudiants'])->name('classes.etudiants');
    
    // Notes
    Route::get('/notes', [\App\Http\Controllers\Enseignant\NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [\App\Http\Controllers\Enseignant\NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [\App\Http\Controllers\Enseignant\NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [\App\Http\Controllers\Enseignant\NoteController::class, 'edit'])->name('notes.edit');
    Route::patch('/notes/{note}', [\App\Http\Controllers\Enseignant\NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [\App\Http\Controllers\Enseignant\NoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('/classes/{classe}/cours-notes', [\App\Http\Controllers\Enseignant\NoteController::class, 'getCoursByClasse'])->name('notes.cours-by-classe');
    
    // Examens
    Route::get('/examens', [\App\Http\Controllers\Enseignant\ExamenController::class, 'index'])->name('examens.index');
    Route::get('/examens/create', [\App\Http\Controllers\Enseignant\ExamenController::class, 'create'])->name('examens.create');
    Route::post('/examens', [\App\Http\Controllers\Enseignant\ExamenController::class, 'store'])->name('examens.store');
    Route::get('/examens/{cours}/{type}', [\App\Http\Controllers\Enseignant\ExamenController::class, 'show'])->name('examens.show');
    
    // Ressources
    Route::get('/ressources', [\App\Http\Controllers\Enseignant\RessourceController::class, 'index'])->name('ressources.index');
    Route::get('/ressources/create', [\App\Http\Controllers\Enseignant\RessourceController::class, 'create'])->name('ressources.create');
    Route::post('/ressources', [\App\Http\Controllers\Enseignant\RessourceController::class, 'store'])->name('ressources.store');
    Route::get('/ressources/{ressource}/edit', [\App\Http\Controllers\Enseignant\RessourceController::class, 'edit'])->name('ressources.edit');
    Route::patch('/ressources/{ressource}', [\App\Http\Controllers\Enseignant\RessourceController::class, 'update'])->name('ressources.update');
    Route::delete('/ressources/{ressource}', [\App\Http\Controllers\Enseignant\RessourceController::class, 'destroy'])->name('ressources.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Enseignant\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [\App\Http\Controllers\Enseignant\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [\App\Http\Controllers\Enseignant\NotificationController::class, 'store'])->name('notifications.store');
});

// Etudiant Routes
Route::middleware(['auth', 'role:etudiant,candidat'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Etudiant\DashboardController::class, 'index'])->name('dashboard');
    
    // Cours
    Route::get('/cours', [\App\Http\Controllers\Etudiant\CoursController::class, 'index'])->name('cours.index');
    Route::get('/cours/{cours}', [\App\Http\Controllers\Etudiant\CoursController::class, 'show'])->name('cours.show');
    
    // Notes
    Route::get('/notes', [\App\Http\Controllers\Etudiant\NoteController::class, 'index'])->name('notes.index');
    
    // Calendrier
    Route::get('/calendrier', [\App\Http\Controllers\Etudiant\CalendrierController::class, 'index'])->name('calendrier.index');
    
    // Ressources
    Route::get('/ressources', [\App\Http\Controllers\Etudiant\RessourceController::class, 'index'])->name('ressources.index');
    Route::get('/ressources/{ressource}', [\App\Http\Controllers\Etudiant\RessourceController::class, 'show'])->name('ressources.show');
    Route::get('/ressources/{ressource}/download', [\App\Http\Controllers\Etudiant\RessourceController::class, 'download'])->name('ressources.download');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Etudiant\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [\App\Http\Controllers\Etudiant\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/lu', [\App\Http\Controllers\Etudiant\NotificationController::class, 'marquerCommeLue'])->name('notifications.lu');
    
    // Candidature
    Route::get('/candidature', [\App\Http\Controllers\Etudiant\CandidatureController::class, 'show'])->name('candidature.show');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidature publique (étudiants/candidats)
    Route::get('/candidature/create', [PublicCandidatureController::class, 'create'])->name('candidature.create');
    Route::get('/candidature/edit', [PublicCandidatureController::class, 'create'])->name('candidature.edit');
    Route::post('/candidature', [PublicCandidatureController::class, 'store'])->name('candidature.store');
});

// Démarrer candidature sans compte (public)
Route::post('/candidature/start', [PublicCandidatureController::class, 'start'])->name('candidature.start');

require __DIR__.'/auth.php';
