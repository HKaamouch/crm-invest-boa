<!-- Statistiques rapides -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <x-investor.stat-card
        icon="chat"
        color="blue"
        title="Interactions"
        :value="$stats['interactions_total']"
        :href="route('investors.timeline', $investor)"
    />

    <x-investor.stat-card
        icon="calendar"
        color="green"
        title="Ce mois"
        :value="$stats['interactions_mois']"
    />

    <x-investor.stat-card
        icon="message"
        color="amber"
        title="Commentaires"
        :value="$stats['commentaires_total']"
    />

    <x-investor.stat-card
        icon="zap"
        color="purple"
        title="Score"
        :value="$stats['score_engagement'] . '/100'"
        tooltip="Score d'engagement basé sur l'activité et l'influence"
    />
</div>
