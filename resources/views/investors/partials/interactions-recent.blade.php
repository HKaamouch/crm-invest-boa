<!-- Interactions récentes -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-slate-900">
                Interactions récentes
                @if($investor->interactions->count() > 0)
                    <span class="text-sm font-normal text-slate-500">({{ $investor->interactions_count }} au total)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <button onclick="openInteractionModal()" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle interaction
                </button>
                @if($investor->interactions->count() > 10)
                    <a href="{{ route('investors.timeline', $investor) }}" class="btn btn-outline btn-sm">
                        Voir tout
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($investor->interactions->count() > 0)
            <div class="space-y-4" id="interactions-container">
                @foreach($investor->interactions->take(10) as $interaction)
                    <x-investor.interaction-card :interaction="$interaction" />
                @endforeach
            </div>

            @if($investor->interactions->count() > 10)
                <div class="mt-6 pt-4 border-t border-slate-200 text-center">
                    <a href="{{ route('investors.timeline', $investor) }}"
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Voir les {{ $investor->interactions_count - 10 }} interactions restantes →
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-slate-500 mb-4">Aucune interaction enregistrée</p>
                <button onclick="openInteractionModal()" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter la première interaction
                </button>
            </div>
        @endif
    </div>
</div>
