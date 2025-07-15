@extends('layouts.app')

@section('title', 'Email - ' . ($email->metadata['objet'] ?? 'Sans objet'))

@section('content')
    @php
        $metadata = is_array($email->metadata) ? $email->metadata : [];
        $objet = $metadata['objet'] ?? 'Sans objet';
        $statut = $metadata['statut'] ?? 'inconnu';
        $isGroupEmail = $metadata['envoi_groupe'] ?? false;
        $groupeId = $metadata['groupe_id'] ?? null;
    @endphp

    <div class="max-w-4xl mx-auto">
        <!-- En-tête de l'email -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
            <div class="p-6 border-b border-slate-200">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center space-x-3">
                        @if($email->type === 'Email envoyé')
                            <div class="rounded-full bg-green-100 p-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                            <span class="text-lg font-medium text-green-700">Email envoyé</span>
                        @else
                            <div class="rounded-full bg-blue-100 p-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <span class="text-lg font-medium text-blue-700">Email reçu</span>
                        @endif

                        @if($isGroupEmail)
                            <span class="badge badge-info">Envoi groupé</span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">
                        @switch($statut)
                            @case('envoyé')
                                <span class="badge badge-success">Envoyé</span>
                                @break
                            @case('reçu')
                                <span class="badge badge-info">Reçu</span>
                                @break
                            @case('en_attente')
                                <span class="badge badge-warning">En attente</span>
                                @break
                            @case('erreur')
                                <span class="badge badge-error">Erreur</span>
                                @break
                            @default
                                <span class="badge badge-neutral">{{ ucfirst($statut) }}</span>
                        @endswitch
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $objet }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Expéditeur -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 mb-2">Expéditeur</h3>
                        @if($email->user)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                    {{ $email->user->initiales ?? substr($email->user->nom_complet, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $email->user->nom_complet }}</p>
                                    <p class="text-sm text-slate-600">{{ $email->user->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-500">Expéditeur non disponible</p>
                        @endif
                    </div>

                    <!-- Destinataire -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 mb-2">Destinataire</h3>
                        @if($email->investor)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $email->investor->avatar_url }}"
                                     alt="{{ $email->investor->nom_complet }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-slate-800">
                                        <a href="{{ route('investors.show', $email->investor) }}"
                                           class="hover:text-blue-600">
                                            {{ $email->investor->nom_complet }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-slate-600">{{ $email->investor->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-500">Destinataire non disponible</p>
                        @endif
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-lg">
                    <div>
                        <span class="text-sm font-medium text-slate-500">Date</span>
                        <p class="text-slate-800">{{ $email->date_interaction->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($isGroupEmail && isset($metadata['nb_destinataires_total']))
                        <div>
                            <span class="text-sm font-medium text-slate-500">Destinataires totaux</span>
                            <p class="text-slate-800">{{ $metadata['nb_destinataires_total'] }} destinataires</p>
                        </div>
                    @endif

                    @if(isset($metadata['type_destinataire']))
                        <div>
                            <span class="text-sm font-medium text-slate-500">Type</span>
                            <p class="text-slate-800">
                                @switch($metadata['type_destinataire'])
                                    @case('to')
                                        Destinataire principal
                                        @break
                                    @case('cc')
                                        Copie conforme (CC)
                                        @break
                                    @case('bcc')
                                        Copie conforme invisible (CCI)
                                        @break
                                    @default
                                        {{ $metadata['type_destinataire'] }}
                                @endswitch
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contenu de l'email -->
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-800 mb-4">Contenu du message</h3>
                <div class="prose max-w-none">
                    <div class="bg-slate-50 rounded-lg p-4 border-l-4 border-blue-500">
                        {!! nl2br(e($email->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Pièces jointes (si disponibles) -->
            @if($email->piece_jointe)
                <div class="p-6 border-t border-slate-200">
                    <h3 class="text-lg font-medium text-slate-800 mb-4">Pièces jointes</h3>
                    <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-slate-800">{{ basename($email->piece_jointe) }}</p>
                            <p class="text-sm text-slate-600">Fichier attaché</p>
                        </div>
                        <a href="{{ route('investors.download-attachment', $email) }}"
                           class="btn btn-sm btn-outline">
                            Télécharger
                        </a>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="p-6 border-t border-slate-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-3">
                        @if($email->investor)
                            <a href="{{ route('investors.show', $email->investor) }}"
                               class="btn btn-outline btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Voir l'investisseur
                            </a>

                            <button onclick="replyToEmail()" class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Répondre
                            </button>
                        @endif
                    </div>

                    <a href="{{ route('emails.index') }}" class="btn btn-outline btn-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Emails liés (si c'est un envoi groupé) -->
        @if($isGroupEmail && $groupeId)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Autres destinataires de cet envoi groupé</h2>
                </div>

                <div class="p-6">
                    @php
                        $groupEmails = \App\Models\Interaction::with('investor')
                            ->where('type', 'Email envoyé')
                            ->whereJsonContains('metadata->groupe_id', $groupeId)
                            ->where('id', '!=', $email->id)
                            ->get();
                    @endphp

                    @if($groupEmails->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($groupEmails as $groupEmail)
                                @if($groupEmail->investor)
                                    <div class="flex items-center space-x-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50">
                                        <img src="{{ $groupEmail->investor->avatar_url }}"
                                             alt="{{ $groupEmail->investor->nom_complet }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-800">{{ $groupEmail->investor->nom_complet }}</p>
                                            <p class="text-sm text-slate-600">{{ $groupEmail->investor->email }}</p>
                                            @if(isset($groupEmail->metadata['type_destinataire']))
                                                <span class="text-xs badge badge-neutral">
                                                    {{ strtoupper($groupEmail->metadata['type_destinataire']) }}
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('emails.show', $groupEmail) }}"
                                           class="btn btn-xs btn-outline">
                                            Voir
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-500">Aucun autre destinataire trouvé pour cet envoi groupé.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de réponse (réutiliser le modal de composition avec des valeurs pré-remplies)
    if($email->investor)
        include('emails.reply-modal', ['investor' => $email->investor, 'originalEmail' => $email])
    endif-->
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('emails.index') }}" class="text-blue-600 hover:text-blue-700">
            Emails
        </a>
    </li>
    <li>
        <span class="text-slate-500">{{ Str::limit($objet, 30) }}</span>
    </li>
@endpush

@push('scripts')
    <script>
        function replyToEmail() {
            // Ouvrir le modal de réponse ou rediriger vers la page de composition
            @if($email->investor)
            // Ici on pourrait implémenter un modal de réponse rapide
            // ou rediriger vers la page de l'investisseur pour répondre
            window.location.href = '{{ route("investors.show", $email->investor) }}#send-email';
            @endif
        }
    </script>
@endpush
