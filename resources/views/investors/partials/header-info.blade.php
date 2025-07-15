<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
    <div class="flex items-start space-x-4">
        <img src="{{ $investor->avatar_url }}"
             alt="{{ $investor->nom_complet }}"
             class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-slate-900">{{ $investor->nom_complet }}</h1>
                <span class="badge {{ $investor->influence_badge }} badge-lg">
                    {{ $investor->niveau_influence }}
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-4 text-slate-600 mb-3">
                @if($investor->categorie)
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full mr-2"
                              style="background-color: {{ $investor->categorie->couleur_hexa }};"></span>
                        <span class="font-medium">{{ $investor->categorie->nom }}</span>
                    </div>
                @endif

                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $investor->pays }}</span>
                </div>

                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ $investor->langue_preferee }}</span>
                </div>
            </div>

            @if($investor->organisation_principale || $investor->organisation)
                <div class="flex items-center text-slate-700 mb-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div class="flex flex-col">
                        <span class="font-medium">
                            @if($investor->organisation_principale)
                                {{ $investor->organisation_principale->raison_sociale }}
                            @else
                                {{ $investor->organisation }}
                            @endif
                        </span>
                        @if($investor->organisation_principale && $investor->organisation_principale->pivot->poste)
                            <span class="text-sm text-slate-500">{{ $investor->organisation_principale->pivot->poste }}</span>
                        @elseif($investor->fonction)
                            <span class="text-sm text-slate-500">{{ $investor->fonction }}</span>
                        @endif
                    </div>
                </div>
            @endif

            @if($investor->tags && count($investor->tags) > 0)
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach($investor->tags as $tag)
                        <span class="badge badge-outline badge-sm">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('investors.index') }}" class="btn btn-outline btn-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la liste
        </a>

        @can('update', $investor)
            <a href="{{ route('investors.edit', $investor) }}" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifier
            </a>
        @endcan

        <button onclick="openEmailModal()" class="btn btn-success btn-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Envoyer Email
        </button>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
                Actions
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-white rounded-box z-50 w-52 p-2 shadow-xl border border-slate-200">
                <li><a href="{{ route('investors.export-pdf', $investor) }}" target="_blank">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exporter PDF
                    </a></li>

                @can('delete', $investor)
                    <li><hr class="my-1"></li>
                    <li><a onclick="confirmDelete()" class="text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </a></li>
                @endcan
            </ul>
        </div>
    </div>
</div>
