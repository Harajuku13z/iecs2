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
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/formations', [FormationController::class, 'index'])->name('formations');

Route::get('/admission', function () {
    return view('public.admission');
})->name('admission');

Route::post('/admission', function () {
    // Logic for candidature submission
    return redirect()->route('home')->with('success', 'Candidature soumise avec succès');
})->name('admission.submit');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    Route::resource('filieres', FiliereController::class);
    Route::resource('niveaux', NiveauController::class);
    Route::resource('classes', ClasseController::class);
    Route::resource('cours', CoursController::class);
    Route::resource('users', UserController::class);
    
    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
    Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::patch('/candidatures/{candidature}/status', [CandidatureController::class, 'updateStatus'])->name('candidatures.updateStatus');
    Route::patch('/candidatures/{candidature}/schedule', [CandidatureController::class, 'schedule'])->name('candidatures.schedule');
    Route::patch('/candidatures/{candidature}/remind', [CandidatureController::class, 'remind'])->name('candidatures.remind');
    Route::patch('/candidatures/{candidature}/assign-class', [CandidatureController::class, 'assignClass'])->name('candidatures.assignClass');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test-email');
    
    Route::get('/home-content', [HomeContentController::class, 'index'])->name('home-content.index');
    Route::post('/home-content', [HomeContentController::class, 'update'])->name('home-content.update');
    
    Route::resource('actualites', ActualiteController::class);
    Route::resource('evenements', EvenementController::class);
});

// Enseignant Routes
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('enseignant.dashboard');
    })->name('dashboard');
});

// Etudiant Routes
Route::middleware(['auth', 'role:etudiant,candidat'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('etudiant.dashboard');
    })->name('dashboard');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
