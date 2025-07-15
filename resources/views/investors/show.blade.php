@extends('layouts.app')

@section('title', $investor->nom_complet)

@section('page-header')
    @include('investors.partials.header-info', ['investor' => $investor])
@endsection

@section('content')
    <!-- Statistiques rapides -->
    @include('investors.partials.quick-stats', ['stats' => $stats])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations détaillées -->
            @include('investors.partials.detailed-info', ['investor' => $investor])

            <!-- Organisations liées -->
            @if($investor->organisations->count() > 0)
                @include('investors.partials.organisations-list', ['investor' => $investor])
            @endif

            <!-- Interactions récentes -->
            @include('investors.partials.interactions-recent', ['investor' => $investor])
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            @include('investors.partials.sidebar.quick-actions', ['investor' => $investor])

            <!-- Commentaires -->
            @include('investors.partials.sidebar.comments-section', ['investor' => $investor])

            <!-- Métadonnées -->
            @include('investors.partials.sidebar.system-info', ['investor' => $investor])
        </div>
    </div>

    <!-- Modales -->
    <x-modals.email-modal :investor="$investor" />
    <x-modals.interaction-modal :investor="$investor" />
    <x-modals.comment-modal :investor="$investor" />
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('investors.index') }}" class="text-blue-600 hover:text-blue-700">
            Investisseurs
        </a>
    </li>
    <li>
        <span class="text-slate-500">{{ $investor->nom_complet }}</span>
    </li>
@endpush

@push('scripts')
    @vite(['resources/js/investor-show.js'])
@endpush
