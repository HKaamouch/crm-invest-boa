<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InvestisseurCommentaire extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'investisseur_commentaires';

    protected $fillable = [
        'investor_id',
        'user_id',
        'commentaire',
        'prive'
    ];

    protected function casts(): array
    {
        return [
            'prive' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Configuration du log d'activité
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['commentaire', 'prive'])
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

    // Scopes
    public function scopePublics($query)
    {
        return $query->where('prive', false);
    }

    public function scopePrives($query)
    {
        return $query->where('prive', true);
    }

    public function scopeRecents($query, $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    // Accessors
    public function getDateFormateeAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function getCommentaireCourteAttribute()
    {
        return strlen($this->commentaire) > 100
            ? substr($this->commentaire, 0, 100) . '...'
            : $this->commentaire;
    }

    public function getAuteurNomAttribute()
    {
        return $this->user->nom_complet ?? $this->user->name;
    }

    public function getTypeIconeAttribute()
    {
        return $this->prive ? 'lock' : 'message-circle';
    }

    public function getTypeBadgeAttribute()
    {
        return $this->prive ? 'badge-warning' : 'badge-info';
    }
}
