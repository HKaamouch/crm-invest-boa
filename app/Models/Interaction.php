<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Interaction extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'investor_id',
        'user_id',
        'type',
        'date_interaction',
        'description',
        'piece_jointe',
        'metadata'
    ];

    protected function casts(): array
    {
        return [
            'date_interaction' => 'date',
            'metadata' => 'array',
        ];
    }

    // Configuration du log d'activité
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relations
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesseurs
    public function getDateInteractionFormateeAttribute()
    {
        return $this->date_interaction->format('d/m/Y');
    }

    public function getDescriptionCourteAttribute()
    {
        return strlen($this->description) > 100
            ? substr($this->description, 0, 100) . '...'
            : $this->description;
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_interaction', '>=', now()->subDays($jours));
    }


    /**
     * Scope pour les emails uniquement
     */
    public function scopeEmails($query)
    {
        return $query->whereIn('type', ['Email envoyé', 'Email reçu']);
    }

    /**
     * Scope pour les emails envoyés
     */
    public function scopeEmailsEnvoyes($query)
    {
        return $query->where('type', 'Email envoyé');
    }

    /**
     * Scope pour les emails reçus
     */
    public function scopeEmailsRecus($query)
    {
        return $query->where('type', 'Email reçu');
    }

    /**
     * Scope pour les emails d'un groupe spécifique
     */
    public function scopeParGroupe($query, $groupeId)
    {
        return $query->whereJsonContains('metadata->groupe_id', $groupeId);
    }

    /**
     * Scope pour rechercher dans les emails
     */
    public function scopeRechercheEmail($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('description', 'like', "%{$terme}%")
                ->orWhereJsonContains('metadata->objet', $terme)
                ->orWhereHas('investor', function($investorQuery) use ($terme) {
                    $investorQuery->where('nom_complet', 'like', "%{$terme}%")
                        ->orWhere('email', 'like', "%{$terme}%");
                });
        });
    }

    // Ajouter ces accesseurs

    /**
     * Obtenir l'objet de l'email depuis les métadonnées
     */
    public function getObjetEmailAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['objet'] ?? 'Sans objet';
    }

    /**
     * Obtenir le statut de l'email depuis les métadonnées
     */
    public function getStatutEmailAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['statut'] ?? 'inconnu';
    }

    /**
     * Vérifier si c'est un email de groupe
     */
    public function getIsEmailGroupeAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['envoi_groupe'] ?? false;
    }

    /**
     * Obtenir l'ID du groupe d'emails
     */
    public function getGroupeIdAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['groupe_id'] ?? null;
    }

    /**
     * Obtenir le type de destinataire (to, cc, bcc)
     */
    public function getTypeDestinataireAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['type_destinataire'] ?? 'to';
    }

    /**
     * Obtenir le nombre total de destinataires pour un envoi groupé
     */
    public function getNbDestinatairesTotalAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        return $metadata['nb_destinataires_total'] ?? 1;
    }

    /**
     * Vérifier si l'interaction est un email
     */
    public function getIsEmailAttribute()
    {
        return in_array($this->type, ['Email envoyé', 'Email reçu']);
    }

    /**
     * Obtenir une version courte du contenu sans l'objet
     */
    public function getContenuSansObjetAttribute()
    {
        if (!$this->is_email) {
            return $this->description;
        }

        $lines = explode("\n", $this->description);

        // Supprimer la première ligne si elle contient "Objet:"
        if (isset($lines[0]) && str_starts_with($lines[0], 'Objet:')) {
            array_shift($lines);

            // Supprimer aussi la ligne vide suivante si elle existe
            if (isset($lines[0]) && trim($lines[0]) === '') {
                array_shift($lines);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Obtenir le badge de couleur selon le type d'email
     */
    public function getBadgeEmailAttribute()
    {
        switch ($this->type) {
            case 'Email envoyé':
                return 'badge-success';
            case 'Email reçu':
                return 'badge-info';
            default:
                return 'badge-neutral';
        }
    }

    /**
     * Obtenir l'icône selon le type d'email
     */
    public function getIconeEmailAttribute()
    {
        switch ($this->type) {
            case 'Email envoyé':
                return 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'; // Icône d'envoi
            case 'Email reçu':
                return 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'; // Icône de réception
            default:
                return 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'; // Icône email générique
        }
    }

    // Ajouter ces méthodes statiques pour les statistiques

    /**
     * Obtenir les statistiques des emails pour un utilisateur
     */
    public static function getEmailStatsForUser($userId)
    {
        $baseQuery = static::emails()->where('user_id', $userId);

        return [
            'total' => $baseQuery->count(),
            'envoyes' => static::emailsEnvoyes()->where('user_id', $userId)->count(),
            'recus' => static::emailsRecus()->where('user_id', $userId)->count(),
            'ce_mois' => $baseQuery->whereMonth('date_interaction', now()->month)
                ->whereYear('date_interaction', now()->year)
                ->count(),
            'cette_semaine' => $baseQuery->whereBetween('date_interaction', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];
    }

    /**
     * Obtenir les emails liés à un groupe
     */
    public static function getEmailsGroupe($groupeId)
    {
        return static::emails()
            ->with(['investor', 'user'])
            ->parGroupe($groupeId)
            ->orderBy('date_interaction', 'desc')
            ->get();
    }

    /**
     * Obtenir les derniers emails pour le dashboard
     */
    public static function getDerniersEmails($limit = 10)
    {
        return static::emails()
            ->with(['investor', 'user'])
            ->orderBy('date_interaction', 'desc')
            ->limit($limit)
            ->get();
    }
}
