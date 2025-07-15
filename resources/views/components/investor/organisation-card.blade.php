<div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg hover:shadow-md transition-shadow">
    <div class="flex items-center space-x-4">
        @if($organisation->logo_path)
            <img src="{{ $organisation->logo_url }}"
                 alt="Logo {{ $organisation->raison_sociale }}"
                 class="w-12 h-12 rounded-lg object-cover border border-slate-200">
        @else
            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif

        <div class="flex-1">
            <h3 class="font-semibold text-slate-900">{{ $organisation->raison_sociale }}</h3>
            <div class="flex flex-col text-sm text-slate-600 mt-1">
                @if($pivot->poste)
                    <span class="font-medium">{{ $pivot->poste }}</span>
                @endif
                <span class="text-xs">{{ $getPeriodText() }}</span>
            </div>
            @if($pivot->notes)
                <p class="text-xs text-slate-500 mt-1">{{ $pivot->notes }}</p>
            @endif
        </div>
    </div>

    <div class="flex items-center space-x-3">
        <span class="badge {{ $getStatusBadgeClass() }} badge-sm">
            {{ $getStatusText() }}
        </span>

        <div class="flex space-x-1">
            <a href="{{ route('organisations.show', $organisation) }}"
               class="btn btn-ghost btn-xs"
               title="Voir l'organisation">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>

            @if($organisation->site_web)
                <a href="{{ $organisation->site_web }}"
                   target="_blank"
                   class="btn btn-ghost btn-xs"
                   title="Site web">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</div>
