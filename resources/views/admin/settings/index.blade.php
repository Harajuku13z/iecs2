@extends('layouts.admin')

@section('title', 'Paramètres du Site')

@section('content')
<h1 class="mb-4">Paramètres du Site</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            @foreach($settings as $setting)
                <div class="mb-3">
                    <label for="{{ $setting->cle }}" class="form-label">
                        <strong>{{ ucfirst(str_replace('_', ' ', $setting->cle)) }}</strong>
                    </label>
                    @if($setting->description)
                        <small class="text-muted d-block">{{ $setting->description }}</small>
                    @endif
                    
                    @if(in_array($setting->cle, ['homepage_title', 'banner_image']))
                        <input type="text" class="form-control" id="{{ $setting->cle }}" 
                               name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                    @elseif($setting->cle === 'inscription_start_date')
                        <input type="date" class="form-control" id="{{ $setting->cle }}" 
                               name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                    @elseif($setting->cle === 'frais_mensuels')
                        <input type="number" class="form-control" id="{{ $setting->cle }}" 
                               name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                    @else
                        <textarea class="form-control" id="{{ $setting->cle }}" 
                                  name="{{ $setting->cle }}" rows="3">{{ $setting->valeur }}</textarea>
                    @endif
                </div>
            @endforeach

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection

