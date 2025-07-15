<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorEmailAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'unique_email',
        'identifier',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('is_active', true);
    }

    // Méthodes utilitaires
    public static function generateUniqueIdentifier()
    {
        do {
            $identifier = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('identifier', $identifier)->exists());

        return $identifier;
    }
}
