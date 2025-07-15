<div class="flex items-start space-x-4 p-4 hover:bg-slate-50 rounded-lg transition-colors">
    <!-- Icon -->
    <div class="flex-shrink-0">
        <div class="w-10 h-10 rounded-full {{ $getTypeColorClass() }} flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $getTypeIcon() !!}
            </svg>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-2">
                <span class="badge {{ $getTypeBadgeClass() }} badge-sm">{{ $interaction->type }}</span>
                <span class="text-sm text-slate-500">{{ $getRelativeTime() }}</span>
                @if($hasAttachment())
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Pièce jointe">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                @endif
            </div>
            <time class="text-xs text-slate-400" datetime="{{ $interaction->date_interaction->toISOString() }}">
                {{ $interaction->date_interaction->format('H:i') }}
            </time>
        </div>

        <div class="text-sm text-slate-900 mb-2">
            {{ $interaction->description_courte }}
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center text-xs text-slate-500">
                <img class="w-5 h-5 rounded-full mr-2"
                     src="https://ui-avatars.com/api/?name={{ urlencode($interaction->user->nom_complet ?? $interaction->user->name) }}&size=20&background=e0e7ff&color=2563eb"
                     alt="{{ $interaction->user->nom_complet ?? $interaction->user->name }}">
                <span>{{ $interaction->user->nom_complet ?? $interaction->user->name }}</span>
            </div>

            @if(strlen($interaction->description) > 100)
                <button onclick="toggleDescription({{ $interaction->id }})"
                        class="text-xs text-blue-600 hover:text-blue-800">
                    Voir plus
                </button>
            @endif
        </div>

        <!-- Full description (hidden by default) -->
        @if(strlen($interaction->description) > 100)
            <div id="full-description-{{ $interaction->id }}" class="hidden mt-3 text-sm text-slate-700 bg-slate-50 p-3 rounded">
                {{ $interaction->description }}
                <button onclick="toggleDescription({{ $interaction->id }})"
                        class="block mt-2 text-xs text-blue-600 hover:text-blue-800">
                    Voir moins
                </button>
            </div>
        @endif

        <!-- Attachment download link -->
        @if($hasAttachment())
            <div class="mt-2">
                <a href="{{ route('investors.download-attachment', $interaction) }}"
                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger la pièce jointe
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDescription(interactionId) {
        const fullDesc = document.getElementById(`full-description-${interactionId}`);
        fullDesc.classList.toggle('hidden');
    }
</script>
