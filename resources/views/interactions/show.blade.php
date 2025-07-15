@extends('layouts.app')

@section('title', 'Détail de l\'interaction')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Détail de l'interaction</h1>
        <a href="{{ route('interactions.index') }}" class="btn btn-outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Informations principales -->
        <div class="col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-slate-800 mb-4">Informations principales</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Type d'interaction</p>
                            <p class="text-base font-medium">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $interaction->type }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500 mb-1">Date et heure</p>
                            <p class="text-base font-medium">
                                {{ \Carbon\Carbon::parse($interaction->date_interaction)->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-md font-medium text-slate-700 mb-2">Description</h3>
                    <div class="bg-slate-50 rounded-lg p-4 whitespace-pre-wrap">
                        {{ $interaction->description }}
                    </div>
                </div>

                @if($interaction->piece_jointe)
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-slate-700 mb-2">Pièce jointe</h3>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <a href="{{ route('interactions.download', $interaction) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                {{ basename($interaction->piece_jointe) }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($interaction->metadata)
                    <div>
                        <h3 class="text-md font-medium text-slate-700 mb-2">Métadonnées</h3>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <pre class="text-sm">{{ json_encode(json_decode($interaction->metadata), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-lg font-medium text-slate-800 mb-4">Agent</h2>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <img class="h-12 w-12 rounded-full" src="{{ asset('images/avatar-placeholder.png') }}" alt="{{ $interaction->user->nom_complet }}">
                    </div>
                    <div class="ml-4">
                        <div class="text-base font-medium text-slate-800">{{ $interaction->user->nom_complet }}</div>
                        <div class="text-sm text-slate-500">{{ $interaction->user->email }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-medium text-slate-800 mb-4">Investisseur</h2>
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12">
                        <img class="h-12 w-12 rounded-full" src="{{ $interaction->investor->avatar_url ?? asset('images/avatar-placeholder.png') }}" alt="{{ $interaction->investor->nom_complet }}">
                    </div>
                    <div class="ml-4">
                        <div class="text-base font-medium text-slate-800">
                            <a href="{{ route('investors.show', $interaction->investor) }}" class="hover:text-blue-600">
                                {{ $interaction->investor->nom_complet }}
                            </a>
                        </div>
                        <div class="text-sm text-slate-500">{{ $interaction->investor->email }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    @if($interaction->investor->telephone_principal)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $interaction->investor->telephone_principal }}
                        </div>
                    @endif

                    @if($interaction->investor->fonction)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $interaction->investor->fonction }}
                        </div>
                    @endif

                    @if($interaction->investor->pays)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $interaction->investor->pays }}
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('investors.show', $interaction->investor) }}" class="btn btn-primary btn-sm w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir la fiche complète
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('interactions.index') }}" class="text-blue-600 hover:text-blue-700">
            Interactions
        </a>
    </li>
    <li class="mx-2 text-slate-400">/</li>
    <li>
        <span class="text-slate-500">Détail</span>
    </li>
@endpush

