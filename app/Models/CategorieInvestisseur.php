<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CategorieInvestisseur extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'categories_investisseurs';

    protected $fillable = [
        'nom',
        'description',
        'couleur_hexa',
        'ordre_affichage',
        'actif'
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'ordre_affichage' => 'integer',
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
    public function investors()
    {
        return $this->hasMany(Investor::class, 'categorie_id');
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre_affichage')->orderBy('nom');
    }

    // Accessors
    public function getCouleurBadgeAttribute()
    {
        return "background-color: {$this->couleur_hexa}; color: white;";
    }

    public function getInvestorsCountAttribute()
    {
        return $this->investors()->count();
    }
}
