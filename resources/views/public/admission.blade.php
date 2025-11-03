@extends('layouts.app')

@section('title', 'Admission - IESCA')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Procédure d'Admission</h1>
            
            <div class="alert alert-info">
                <h5>📝 Informations importantes</h5>
                <ul>
                    <li>Les inscriptions débutent le {{ \App\Models\Setting::get('inscription_start_date', '2025-01-15') }}</li>
                    <li>Frais de scolarité: {{ \App\Models\Setting::get('frais_mensuels', '50000') }} FCFA/mois</li>
                    <li>Documents requis: Diplôme, Certificat de naissance, Photos d'identité</li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Formulaire de Pré-inscription</h4>
                </div>
                <div class="card-body">
                    @if(auth()->check())
                        @if(auth()->user()->isCandidat() && auth()->user()->candidature)
                            <div class="alert alert-success">
                                <h5>✅ Candidature déjà soumise</h5>
                                <p>Votre candidature est actuellement en cours de traitement. Statut: <strong>{{ auth()->user()->candidature->statut }}</strong></p>
                                <a href="{{ route('etudiant.dashboard') }}" class="btn btn-primary">
                                    Voir le statut de ma candidature
                                </a>
                            </div>
                        @else
                            <form action="{{ route('admission.submit') }}" method="POST">
                                @csrf
                                <div class="alert alert-info">
                                    Vous êtes déjà connecté. Votre candidature sera associée à votre compte.
                                </div>
                                <button type="submit" class="btn btn-primary">Soumettre ma candidature</button>
                            </form>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <strong>Important:</strong> Vous devez d'abord créer un compte pour soumettre votre candidature.
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-primary">Créer un compte</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">J'ai déjà un compte</a>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Étapes du processus</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Création de compte sur notre plateforme</li>
                        <li>Soumission du dossier de candidature en ligne</li>
                        <li>Vérification administrative des documents</li>
                        <li>Étude du dossier par le comité d'admission</li>
                        <li>Notification de la décision</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

