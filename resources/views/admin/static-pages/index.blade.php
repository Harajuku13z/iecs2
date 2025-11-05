@extends('layouts.admin')

@section('title', 'Pages Statiques')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📄 Pages Statiques</h1>
    <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary">
        ➕ Créer une nouvelle page
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Menu</th>
                        <th>Parent</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <strong>{{ $page->titre }}</strong>
                                @if($page->description)
                                    <br><small class="text-muted">{{ Str::limit($page->description, 50) }}</small>
                                @endif
                            </td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>
                                @if($page->afficher_menu)
                                    <span class="badge bg-success">Dans le menu</span>
                                    <br><small>{{ $page->menu_nom }}</small>
                                @else
                                    <span class="badge bg-secondary">Hors menu</span>
                                @endif
                            </td>
                            <td>
                                @if($page->menu_parent)
                                    <span class="badge bg-info">{{ $page->menu_parent }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $page->menu_ordre }}</td>
                            <td>
                                @if($page->publie)
                                    <span class="badge bg-success">Publiée</span>
                                @else
                                    <span class="badge bg-warning">Brouillon</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('static-page.show', $page->slug) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Voir">
                                        👁️
                                    </a>
                                    <a href="{{ route('admin.static-pages.edit', $page) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Modifier">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.static-pages.destroy', $page) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette page ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucune page statique créée pour le moment.
                                <br>
                                <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary mt-2">
                                    Créer la première page
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
            <div class="mt-4">
                {{ $pages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

