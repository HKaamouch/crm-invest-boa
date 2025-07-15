<!-- Informations détaillées -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <h2 class="text-lg font-semibold text-slate-900">Informations détaillées</h2>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Email -->
            <div>
                <dt class="text-sm font-medium text-slate-500">Email</dt>
                <dd class="mt-1 text-sm text-slate-900">
                    <a href="mailto:{{ $investor->email }}" class="text-blue-600 hover:text-blue-800">
                        {{ $investor->email }}
                    </a>
                </dd>
            </div>

            <!-- Téléphone -->
            @if($investor->telephone)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        <a href="tel:{{ $investor->telephone }}" class="text-blue-600 hover:text-blue-800">
                            {{ $investor->telephone }}
                        </a>
                    </dd>
                </div>
            @endif

            <!-- Mobile -->
            @if($investor->mobile)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Mobile</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        <a href="tel:{{ $investor->mobile }}" class="text-blue-600 hover:text-blue-800">
                            {{ $investor->mobile }}
                        </a>
                    </dd>
                </div>
            @endif

            <!-- Fonction -->
            @if($investor->fonction)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Fonction</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $investor->fonction }}</dd>
                </div>
            @endif

            <!-- Organisation (texte) -->
            @if($investor->organisation && !$investor->organisation_principale)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Organisation</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $investor->organisation }}</dd>
                </div>
            @endif

            <!-- Langue préférée -->
            <div>
                <dt class="text-sm font-medium text-slate-500">Langue préférée</dt>
                <dd class="mt-1 text-sm text-slate-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $investor->langue_preferee }}
                    </span>
                </dd>
            </div>

            <!-- Niveau d'influence -->
            <div>
                <dt class="text-sm font-medium text-slate-500">Niveau d'influence</dt>
                <dd class="mt-1 text-sm text-slate-900">
                    <span class="badge {{ $investor->influence_badge }}">
                        {{ $investor->niveau_influence }}
                    </span>
                </dd>
            </div>

            <!-- Dernière interaction -->
            @if($investor->derniere_interaction)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Dernière interaction</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $investor->derniere_interaction_formatee }}</dd>
                </div>
            @endif

            <!-- Email unique -->
            @if($investor->emailAddress)
                <div>
                    <dt class="text-sm font-medium text-slate-500">Email de réception</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        <code class="bg-slate-100 px-2 py-1 rounded text-xs">{{ $investor->emailAddress->unique_email }}</code>
                    </dd>
                </div>
            @endif
        </dl>

        <!-- Remarques -->
        @if($investor->remarques)
            <div class="mt-6 pt-6 border-t border-slate-200">
                <dt class="text-sm font-medium text-slate-500 mb-2">Remarques</dt>
                <dd class="text-sm text-slate-900 leading-relaxed">{{ $investor->remarques }}</dd>
            </div>
        @endif
    </div>
</div>
