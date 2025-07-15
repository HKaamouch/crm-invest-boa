<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Investisseur - {{ $investor->nom_complet }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 0 20px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #64748b;
            padding: 8px 0;
            width: 30%;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            padding: 8px 0;
            padding-left: 15px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-primary {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-error {
            background-color: #fecaca;
            color: #991b1b;
        }

        .interaction-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
            background-color: #f8fafc;
        }

        .interaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .interaction-type {
            font-weight: bold;
            color: #2563eb;
        }

        .interaction-date {
            color: #64748b;
            font-size: 11px;
        }

        .interaction-description {
            line-height: 1.5;
            margin-top: 8px;
        }

        .organisation-item {
            border-left: 4px solid #2563eb;
            padding-left: 12px;
            margin-bottom: 12px;
        }

        .organisation-name {
            font-weight: bold;
            color: #1e293b;
        }

        .organisation-role {
            color: #64748b;
            font-size: 11px;
            margin-top: 2px;
        }

        .comment-item {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: bold;
            color: #2563eb;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .comment-text {
            line-height: 1.4;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<!-- En-tête -->
<div class="header">
    <h1>{{ $investor->nom_complet }}</h1>
    <p>Fiche investisseur - Bank of Africa</p>
    <div style="margin-top: 10px; font-size: 12px;">
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</div>

<div class="content">
    <!-- Informations personnelles -->
    <div class="section">
        <h2 class="section-title">Informations personnelles</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet</div>
                <div class="info-value">{{ $investor->nom_complet }}</div>
            </div>
            @if($investor->civilite)
                <div class="info-row">
                    <div class="info-label">Civilité</div>
                    <div class="info-value">{{ $investor->civilite }}</div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $investor->email }}</div>
            </div>
            @if($investor->telephone)
                <div class="info-row">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $investor->telephone }}</div>
                </div>
            @endif
            @if($investor->mobile)
                <div class="info-row">
                    <div class="info-label">Mobile</div>
                    <div class="info-value">{{ $investor->mobile }}</div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Pays</div>
                <div class="info-value">{{ $investor->pays }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Langue préférée</div>
                <div class="info-value">{{ $investor->langue_preferee }}</div>
            </div>
            @if($investor->categorie)
                <div class="info-row">
                    <div class="info-label">Catégorie</div>
                    <div class="info-value">
                        <span class="badge badge-primary">{{ $investor->categorie->nom }}</span>
                    </div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Niveau d'influence</div>
                <div class="info-value">
                        <span class="badge {{ $investor->niveau_influence === 'Critique' ? 'badge-error' : ($investor->niveau_influence === 'Élevé' ? 'badge-success' : ($investor->niveau_influence === 'Moyen' ? 'badge-warning' : 'badge-primary')) }}">
                            {{ $investor->niveau_influence }}
                        </span>
                </div>
            </div>
            @if($investor->fonction)
                <div class="info-row">
                    <div class="info-label">Fonction</div>
                    <div class="info-value">{{ $investor->fonction }}</div>
                </div>
            @endif
            @if($investor->tags && count($investor->tags) > 0)
                <div class="info-row">
                    <div class="info-label">Tags</div>
                    <div class="info-value">{{ implode(', ', $investor->tags) }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Organisations -->
    @if($investor->organisations->count() > 0)
        <div class="section">
            <h2 class="section-title">Organisations ({{ $investor->organisations->count() }})</h2>
            @foreach($investor->organisations as $organisation)
                <div class="organisation-item no-break">
                    <div class="organisation-name">{{ $organisation->raison_sociale }}</div>
                    @if($organisation->pivot->poste)
                        <div class="organisation-role">{{ $organisation->pivot->poste }}</div>
                    @endif
                    <div class="organisation-role">
                        @if($organisation->pivot->date_debut)
                            Depuis {{ \Carbon\Carbon::parse($organisation->pivot->date_debut)->format('m/Y') }}
                        @endif
                        @if($organisation->pivot->actuel)
                            <span class="badge badge-success" style="margin-left: 5px;">Actuel</span>
                        @endif
                    </div>
                    @if($organisation->pivot->notes)
                        <div style="margin-top: 5px; font-style: italic; color: #64748b;">
                            {{ $organisation->pivot->notes }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Remarques -->
    @if($investor->remarques)
        <div class="section">
            <h2 class="section-title">Remarques</h2>
            <div style="line-height: 1.6;">
                {{ $investor->remarques }}
            </div>
        </div>
    @endif

    <!-- Interactions récentes -->
    @if($investor->interactions->count() > 0)
        <div class="section page-break">
            <h2 class="section-title">Historique des interactions ({{ $investor->interactions->count() }})</h2>
            @foreach($investor->interactions->take(20) as $interaction)
                <div class="interaction-item no-break">
                    <div class="interaction-header">
                        <span class="interaction-type">{{ $interaction->type }}</span>
                        <span class="interaction-date">{{ $interaction->date_interaction->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="interaction-description">
                        {{ $interaction->description }}
                    </div>
                    <div style="margin-top: 8px; font-size: 10px; color: #64748b;">
                        Par {{ $interaction->user->nom_complet ?? $interaction->user->name }}
                        @if($interaction->piece_jointe)
                            • Pièce jointe incluse
                        @endif
                    </div>
                </div>
            @endforeach

            @if($investor->interactions->count() > 20)
                <div style="text-align: center; margin-top: 15px; font-style: italic; color: #64748b;">
                    ... et {{ $investor->interactions->count() - 20 }} autres interactions
                </div>
            @endif
        </div>
    @endif

    <!-- Commentaires publics -->
    @php
        $publicComments = $investor->commentaires->where('prive', false);
    @endphp
    @if($publicComments->count() > 0)
        <div class="section">
            <h2 class="section-title">Commentaires ({{ $publicComments->count() }})</h2>
            @foreach($publicComments->take(10) as $commentaire)
                <div class="comment-item no-break">
                    <div class="comment-author">
                        {{ $commentaire->auteur_nom }} - {{ $commentaire->created_at->format('d/m/Y à H:i') }}
                    </div>
                    <div class="comment-text">
                        {{ $commentaire->commentaire }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Informations système -->
    <div class="section">
        <h2 class="section-title">Informations système</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Créé le</div>
                <div class="info-value">{{ $investor->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Modifié le</div>
                <div class="info-value">{{ $investor->updated_at->format('d/m/Y à H:i') }}</div>
            </div>
            @if($investor->derniere_interaction)
                <div class="info-row">
                    <div class="info-label">Dernière interaction</div>
                    <div class="info-value">{{ $investor->derniere_interaction->format('d/m/Y à H:i') }}</div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Score d'engagement</div>
                <div class="info-value">{{ $investor->score_engagement }}/100</div>
            </div>
            @if($investor->emailAddress)
                <div class="info-row">
                    <div class="info-label">Email de réception</div>
                    <div class="info-value">{{ $investor->emailAddress->unique_email }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Pied de page -->
<div class="footer">
    <div>Bank of Africa - CRM Investisseurs</div>
    <div>Document confidentiel généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
</body>
</html>
