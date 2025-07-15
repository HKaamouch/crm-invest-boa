<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Organisation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'raison_sociale',
        'logo_path',
        'adresse',
        'ville',
        'pays',
        'telephone',
        'fax',
        'email',
        'site_web',
        'description',
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

    // Relations Many-to-Many
    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'investisseur_organisations')
            ->withPivot('poste', 'date_debut', 'date_fin', 'actuel', 'notes')
            ->withTimestamps();
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_organisations')
            ->withPivot('poste', 'date_debut', 'date_fin', 'actuel')
            ->withTimestamps();
    }

    // Relations pour les pivots actuels
    public function investorsActuels()
    {
        return $this->investors()->wherePivot('actuel', true);
    }

    public function contactsActuels()
    {
        return $this->contacts()->wherePivot('actuel', true);
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParPays($query, $pays)
    {
        return $query->where('pays', $pays);
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('raison_sociale', 'like', "%{$terme}%")
                ->orWhere('description', 'like', "%{$terme}%")
                ->orWhere('ville', 'like', "%{$terme}%");
        });
    }

    // Accessors
    public function getAdresseCompleteAttribute()
    {
        $parts = array_filter([
            $this->adresse,
            $this->ville,
            $this->pays
        ]);

        return implode(', ', $parts);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/logos/' . $this->logo_path);
        }

        // Logo par défaut basé sur les initiales
        $initiales = strtoupper(substr($this->raison_sociale, 0, 2));
        return "https://ui-avatars.com/api/?name={$initiales}&background=2563eb&color=ffffff&size=64";
    }

    public function getTotalInvestorsAttribute()
    {
        return $this->investors()->count();
    }

    public function getTotalContactsAttribute()
    {
        return $this->contacts()->count();
    }
}
