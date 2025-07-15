@<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Investor extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'civilite',
        'prenom',
        'nom',
        'categorie_id',
        'pays',
        'email',
        'telephone',
        'mobile',
        'fonction',
        'langue_preferee',
        'niveau_influence',
        'remarques',
        'tags',
        'derniere_interaction'
    ];

    protected function casts(): array
    {
        return [
            'derniere_interaction' => 'datetime',
            'tags' => 'array',
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
    public function categorie()
    {
        return $this->belongsTo(CategorieInvestisseur::class, 'categorie_id');
    }

    public function categorieInvestisseur()
    {
        return $this->belongsTo(CategorieInvestisseur::class, 'categorie_id');
    }

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'investisseur_organisations')
            ->withPivot('poste', 'date_debut', 'date_fin', 'actuel', 'notes')
            ->withTimestamps();
    }

    public function organisationsActuelles()
    {
        return $this->organisations()->wherePivot('actuel', true);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class)->orderBy('date_interaction', 'desc');
    }

    public function commentaires()
    {
        return $this->hasMany(InvestisseurCommentaire::class)->orderBy('created_at', 'desc');
    }

    public function emailAddress()
    {
        return $this->hasOne(InvestorEmailAddress::class);
    }

    // Scopes pour les filtres
    public function scopeParCategorie($query, $categorieId)
    {
        return $query->where('categorie_id', $categorieId);
    }

    public function scopeParPays($query, $pays)
    {
        return $query->where('pays', $pays);
    }

    public function scopeParLangue($query, $langue)
    {
        return $query->where('langue_preferee', $langue);
    }

    public function scopeParInfluence($query, $niveau)
    {
        return $query->where('niveau_influence', $niveau);
    }

    public function scopeAvecTags($query, array $tags)
    {
        return $query->where(function($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('prenom', 'like', "%{$terme}%")
                ->orWhere('nom', 'like', "%{$terme}%")
                ->orWhere('nom_complet', 'like', "%{$terme}%")
                ->orWhere('email', 'like', "%{$terme}%")
                ->orWhere('organisation', 'like', "%{$terme}%")
                ->orWhere('fonction', 'like', "%{$terme}%")
                ->orWhere('remarques', 'like', "%{$terme}%");
        });
    }

    public function scopeActifsDepuis($query, $jours)
    {
        return $query->where('derniere_interaction', '>=', now()->subDays($jours));
    }

    // Accessors
    public function getNomCompletAttribute($value)
    {
        // Si le nouveau format existe, l'utiliser
        if ($this->prenom && $this->nom) {
            return trim($this->prenom . ' ' . $this->nom);
        }

        // Sinon utiliser l'ancien format
        return $value;
    }

    public function getInitialesAttribute()
    {
        if ($this->prenom && $this->nom) {
            return strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
        }

        // Fallback sur nom_complet
        $parts = explode(' ', $this->nom_complet);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }

    public function getInfluenceBadgeAttribute()
    {
        $badges = [
            'Faible' => 'badge-neutral',
            'Moyen' => 'badge-warning',
            'Élevé' => 'badge-success',
            'Critique' => 'badge-error'
        ];
        return $badges[$this->niveau_influence] ?? 'badge-neutral';
    }

    public function getInfluenceColorAttribute()
    {
        $colors = [
            'Faible' => '#6B7280',
            'Moyen' => '#F59E0B',
            'Élevé' => '#10B981',
            'Critique' => '#EF4444'
        ];
        return $colors[$this->niveau_influence] ?? '#6B7280';
    }

    public function getAvatarUrlAttribute()
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->nom_complet) . "&background=2563eb&color=ffffff&size=128";
    }

    public function getInteractionsCountAttribute()
    {
        return $this->interactions()->count();
    }

    public function getCommentairesCountAttribute()
    {
        return $this->commentaires()->count();
    }

    public function getDerniereInteractionFormateeAttribute()
    {
        return $this->derniere_interaction?->format('d/m/Y');
    }

    public function getTelephonePrincipalAttribute()
    {
        return $this->mobile ?: $this->telephone;
    }

    public function getOrganisationPrincipaleAttribute()
    {
        return $this->organisationsActuelles()->first();
    }

    public function getTagsListeAttribute()
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    public function getScoreEngagementAttribute()
    {
        $score = 0;

        // Points pour les interactions récentes
        $interactionsRecentes = $this->interactions()->where('date_interaction', '>=', now()->subDays(90))->count();
        $score += min($interactionsRecentes * 10, 50);

        // Points pour niveau d'influence
        $influencePoints = [
            'Faible' => 5,
            'Moyen' => 15,
            'Élevé' => 30,
            'Critique' => 50
        ];
        $score += $influencePoints[$this->niveau_influence] ?? 0;

        // Points pour les commentaires
        $commentaires = $this->commentaires()->count();
        $score += min($commentaires * 5, 25);

        return min($score, 100);
    }
}
