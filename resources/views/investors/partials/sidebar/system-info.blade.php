<!-- Métadonnées système -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informations système</h3>
    <dl class="space-y-4">
        <!-- Date de création -->
        <div>
            <dt class="text-sm font-medium text-slate-500">Créé le</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $investor->created_at->format('d/m/Y à H:i') }}</dd>
        </div>

        <!-- Date de modification -->
        <div>
            <dt class="text-sm font-medium text-slate-500">Modifié le</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $investor->updated_at->format('d/m/Y à H:i') }}</dd>
        </div>

        <!-- Dernière interaction -->
        @if($investor->derniere_interaction)
            <div>
                <dt class="text-sm font-medium text-slate-500">Dernière interaction</dt>
                <dd class="mt-1 text-sm text-slate-900">{{ $investor->derniere_interaction->format('d/m/Y à H:i') }}</dd>
            </div>
        @endif

        <!-- Email unique -->
        @if($investor->emailAddress)
            <div>
                <dt class="text-sm font-medium text-slate-500">Email de réception</dt>
                <dd class="mt-1">
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded">{{ $investor->emailAddress->unique_email }}</code>
                    <div class="text-xs text-slate-500 mt-1">
                        Identifiant: {{ $investor->emailAddress->identifier }}
                    </div>
                </dd>
            </div>
        @endif

        <!-- Score d'engagement -->
        <div>
            <dt class="text-sm font-medium text-slate-500">Score d'engagement</dt>
            <dd class="mt-1">
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $investor->score_engagement }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-slate-900">{{ $investor->score_engagement }}/100</span>
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    Basé sur l'activité et l'influence
                </div>
            </dd>
        </div>

        <!-- Statistiques rapides -->
        <div class="pt-4 border-t border-slate-200">
            <dt class="text-sm font-medium text-slate-500 mb-3">Statistiques</dt>
            <dd class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Interactions totales</span>
                    <span class="font-medium text-slate-900">{{ $investor->interactions_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Commentaires</span>
                    <span class="font-medium text-slate-900">{{ $investor->commentaires_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Organisations</span>
                    <span class="font-medium text-slate-900">{{ $investor->organisations->count() }}</span>
                </div>
            </dd>
        </div>

        <!-- Tags système (si disponibles) -->
        @if($investor->tags && count($investor->tags) > 0)
            <div class="pt-4 border-t border-slate-200">
                <dt class="text-sm font-medium text-slate-500 mb-2">Tags</dt>
                <dd class="flex flex-wrap gap-1">
                    @foreach($investor->tags as $tag)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                            {{ $tag }}
                        </span>
                    @endforeach
                </dd>
            </div>
        @endif
    </dl>
</div>
