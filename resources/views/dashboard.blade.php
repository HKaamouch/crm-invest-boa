@extends('layouts.app')

@section('title', 'Dashboard')

@php
    use App\Models\Interaction;
    use App\Models\CategorieInvestisseur;
    use App\Models\Investor;
    use App\Models\Organisation;
@endphp

@section('page-header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-600 mt-1">Vue d'ensemble de votre CRM Investisseurs</p>
        </div>

    </div>
@endsection

@section('content')
    @php
        // Récupérer les données pour les cartes statistiques
        $totalInvestisseurs = Investor::count();
        $interactionsCeMois = Interaction::whereMonth('date_interaction', now()->month)
                                         ->whereYear('date_interaction', now()->year)
                                         ->count();
        $emailsEnvoyes = Interaction::emailsEnvoyes()->count();
        $totalOrganisations = Organisation::count();
    @endphp

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total Investisseurs</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalInvestisseurs }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Interactions ce mois</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $interactionsCeMois }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Emails envoyés</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $emailsEnvoyes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Nombre d'Organisations</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalOrganisations }}</p>
                </div>
            </div>
        </div>
    </div>

    @php
        // Récupérer les statistiques des emails
        $emailStats = [
            'total' => Interaction::emails()->count(),
            'envoyes' => Interaction::emailsEnvoyes()->count(),
            'recus' => Interaction::emailsRecus()->count(),
            'aujourd_hui' => Interaction::emails()->whereDate('date_interaction', today())->count(),
            'cette_semaine' => Interaction::emails()->whereBetween('date_interaction', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'ce_mois' => Interaction::emails()->whereMonth('date_interaction', now()->month)
                                              ->whereYear('date_interaction', now()->year)
                                              ->count(),
        ];

        // Récupérer les derniers emails
        $derniersEmails = Interaction::emails()
            ->with(['investor', 'user'])
            ->orderBy('date_interaction', 'desc')
            ->limit(5)
            ->get();

        // Récupérer les données pour le camembert des catégories d'investisseurs
        $categoriesData = CategorieInvestisseur::withCount('investors')
            ->get()
            ->filter(function($categorie) {
                return $categorie->investors_count > 0;
            })
            ->map(function($categorie) {
                return [
                    'name' => $categorie->nom,
                    'value' => $categorie->investors_count,
                    'color' => $categorie->couleur_hexa ?? '#'.dechex(rand(0x000000, 0xFFFFFF)) // Couleur aléatoire si non définie
                ];
            });

        // Calculer le total des investisseurs pour les pourcentages
        $totalInvestisseurs = $categoriesData->sum('value');

        // Récupérer les activités récentes
        $recentActivities = Interaction::with(['investor', 'user'])
            ->orderBy('date_interaction', 'desc')
            ->limit(7)
            ->get();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistiques rapides -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Emails</h3>
                <a href="{{ route('emails.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Voir tout →
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($emailStats['total']) }}</div>
                    <div class="text-xs text-blue-600 font-medium">Total emails</div>
                </div>

                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($emailStats['envoyes']) }}</div>
                    <div class="text-xs text-green-600 font-medium">Envoyés</div>
                </div>

                <div class="text-center p-3 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($emailStats['ce_mois']) }}</div>
                    <div class="text-xs text-purple-600 font-medium">Ce mois</div>
                </div>

                <div class="text-center p-3 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ number_format($emailStats['cette_semaine']) }}</div>
                    <div class="text-xs text-orange-600 font-medium">Cette semaine</div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-200">
                <button onclick="openComposeModal()" class="btn btn-primary btn-sm w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Composer un email
                </button>
            </div>
        </div>

        <!-- Répartition par catégories d'investisseurs (Camembert) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Répartition par catégories d'investisseurs</h3>
                <a href="{{ route('categories-investisseurs.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Gérer →
                </a>
            </div>

            @if($categoriesData->isNotEmpty() && $totalInvestisseurs > 0)
                <div class="flex flex-col md:flex-row">
                    <div class="h-64 w-full md:w-2/3">
                        <canvas id="categories-pie-chart"></canvas>
                    </div>
                    <div class="md:w-1/3 mt-4 md:mt-0 md:pl-4">
                        <div class="space-y-2">
                            @foreach($categoriesData as $categorie)
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $categorie['color'] }}"></div>
                                    <div class="text-sm">
                                        <span class="font-medium">{{ $categorie['name'] }}</span>
                                        <span class="text-slate-500 ml-1">
                                            {{ $categorie['value'] }} ({{ round(($categorie['value'] / $totalInvestisseurs) * 100) }}%)
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-slate-500 text-sm">Aucune donnée disponible</p>
                    <a href="{{ route('categories-investisseurs.create') }}" class="btn btn-outline btn-sm mt-3">
                        Ajouter une catégorie
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Derniers emails -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Derniers emails</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('emails.index', ['type' => 'Email envoyé']) }}"
                       class="text-xs text-green-600 hover:text-green-700">
                        Envoyés
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('emails.index', ['type' => 'Email reçu']) }}"
                       class="text-xs text-blue-600 hover:text-blue-700">
                        Reçus
                    </a>
                </div>
            </div>

            @if($derniersEmails->count() > 0)
                <div class="space-y-3">
                    @foreach($derniersEmails as $email)
                        @php
                            $metadata = is_array($email->metadata) ? $email->metadata : [];
                            $objet = $metadata['objet'] ?? 'Sans objet';
                        @endphp
                        <div class="flex items-start space-x-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                            <div class="flex-shrink-0">
                                @if($email->type === 'Email envoyé')
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">
                                            <a href="{{ route('emails.show', $email) }}" class="hover:text-blue-600">
                                                {{ $objet }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-slate-600 truncate">
                                            @if($email->investor)
                                                {{ $email->type === 'Email envoyé' ? 'À' : 'De' }} {{ $email->investor->nom_complet }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-xs text-slate-500 ml-2 flex-shrink-0">
                                    {{ $email->date_interaction->diffForHumans() }}
                                </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-500 text-sm">Aucun email récent</p>
                    <button onclick="openComposeModal()" class="btn btn-outline btn-sm mt-3">
                        Composer le premier email
                    </button>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Activité récente</h3>
            <div class="space-y-4">
                @if($recentActivities->count() > 0)
                    @foreach($recentActivities as $activity)
                        <div class="flex items-center space-x-4">
                            @php
                                $colorClass = 'bg-blue-400';
                                if (str_contains($activity->type, 'Email')) {
                                    $colorClass = 'bg-green-400';
                                } elseif (str_contains($activity->type, 'Appel')) {
                                    $colorClass = 'bg-amber-400';
                                } elseif (str_contains($activity->type, 'Rendez-vous')) {
                                    $colorClass = 'bg-purple-400';
                                }
                            @endphp
                            <div class="w-2 h-2 {{ $colorClass }} rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-900">{{ $activity->type }}:
                                    @if($activity->investor)
                                        <strong>{{ $activity->investor->nom_complet }}</strong>
                                    @else
                                        <strong>Investisseur inconnu</strong>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-500">{{ $activity->date_interaction->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-slate-500 text-sm">Aucune activité récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Graphique de l'activité email des 30 derniers jours --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Activité email - 30 derniers jours</h3>

        @php
            // Préparer les données pour le graphique des 30 derniers jours
            $emailActivity = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $emailActivity[] = [
                    'date' => $date->format('d/m'),
                    'envoyes' => Interaction::emailsEnvoyes()->whereDate('date_interaction', $date)->count(),
                    'recus' => Interaction::emailsRecus()->whereDate('date_interaction', $date)->count(),
                ];
            }
        @endphp

        <div class="h-64">
            <canvas id="email-activity-chart"></canvas>
        </div>
    </div>

    @push('scripts')
        <!-- Inclure Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

        <script>
            // Vérifier si Chart.js est chargé correctement
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM Content Loaded');

                if (typeof Chart === 'undefined') {
                    console.error('Chart.js n\'est pas chargé correctement!');
                    return;
                }

                console.log('Chart.js est disponible:', typeof Chart);

                const emailData = @json($emailActivity);
                const categoriesData = @json($categoriesData);

                console.log('Categories Data:', categoriesData);

                // S'assurer que le DOM est complètement chargé
                setTimeout(function() {
                    // Graphique d'activité email
                    const ctxEmail = document.getElementById('email-activity-chart');
                    if (ctxEmail) {
                        try {
                            console.log('Élément email-activity-chart trouvé:', ctxEmail);
                            new Chart(ctxEmail, {
                                type: 'line',
                                data: {
                                    labels: emailData.map(d => d.date),
                                    datasets: [
                                        {
                                            label: 'Emails envoyés',
                                            data: emailData.map(d => d.envoyes),
                                            borderColor: '#10B981',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            tension: 0.3
                                        },
                                        {
                                            label: 'Emails reçus',
                                            data: emailData.map(d => d.recus),
                                            borderColor: '#3B82F6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            tension: 0.3
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                            console.log('Graphique d\'activité email créé avec succès');
                        } catch (error) {
                            console.error('Erreur lors de la création du graphique d\'activité email:', error);
                        }
                    } else {
                        console.warn('Élément email-activity-chart non trouvé');
                    }

                    // Graphique en camembert des catégories d'investisseurs
                    const ctxPie = document.getElementById('categories-pie-chart');
                    if (ctxPie && categoriesData && categoriesData.length > 0) {
                        try {
                            console.log('Élément categories-pie-chart trouvé:', ctxPie);
                            console.log('Tentative de création du camembert...');
                            new Chart(ctxPie, {
                                type: 'doughnut',
                                data: {
                                    labels: categoriesData.map(d => d.name),
                                    datasets: [{
                                        data: categoriesData.map(d => d.value),
                                        backgroundColor: categoriesData.map(d => d.color),
                                        borderWidth: 1,
                                        borderColor: '#ffffff'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '60%',
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.raw;
                                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    const percentage = Math.round((value / total) * 100);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            console.log('Graphique en camembert créé avec succès');
                        } catch (error) {
                            console.error('Erreur lors de la création du graphique en camembert:', error);
                        }
                    } else {
                        console.warn('Élément categories-pie-chart non trouvé ou données vides:', {
                            elementExists: !!ctxPie,
                            dataExists: !!categoriesData,
                            dataLength: categoriesData ? categoriesData.length : 0
                        });
                    }
                }, 100); // Petit délai pour s'assurer que le DOM est prêt
            });
        </script>
    @endpush
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Dashboard</span>
    </li>
@endpush
