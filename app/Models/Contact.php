<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'telephone',
        'mobile',
        'notes',
        'actif'
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
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
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'contact_organisations')
            ->withPivot('poste', 'date_debut', 'date_fin', 'actuel')
            ->withTimestamps();
    }

    public function organisationsActuelles()
    {
        return $this->organisations()->wherePivot('actuel', true);
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('prenom', 'like', "%{$terme}%")
                ->orWhere('nom', 'like', "%{$terme}%")
                ->orWhere('email', 'like', "%{$terme}%")
                ->orWhere('notes', 'like', "%{$terme}%");
        });
    }

    // Accessors
    public function getNomCompletAttribute()
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getTelephonePrincipalAttribute()
    {
        return $this->mobile ?: $this->telephone;
    }

    public function getInitialesAttribute()
    {
        return strtoupper(
            substr($this->prenom, 0, 1) . substr($this->nom, 0, 1)
        );
    }

    public function getAvatarUrlAttribute()
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->nom_complet) . "&background=2563eb&color=ffffff&size=64";
    }

    public function getTotalOrganisationsAttribute()
    {
        return $this->organisations()->count();
    }
}
