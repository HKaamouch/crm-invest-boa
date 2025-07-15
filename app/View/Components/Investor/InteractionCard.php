<?php

namespace App\View\Components\Investor;

use App\Models\Interaction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InteractionCard extends Component
{
    public Interaction $interaction;

    /**
     * Create a new component instance.
     */
    public function __construct(Interaction $interaction)
    {
        $this->interaction = $interaction;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.investor.interaction-card');
    }

    /**
     * Get the icon for the interaction type.
     */
    public function getTypeIcon(): string
    {
        return match ($this->interaction->type) {
            'Email', 'Email envoyé', 'Email reçu' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
            'Appel' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>',
            'Réunion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
            default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
        };
    }

    /**
     * Get the color class for the interaction type.
     */
    public function getTypeColorClass(): string
    {
        return match ($this->interaction->type) {
            'Email envoyé' => 'bg-blue-50 text-blue-600',
            'Email reçu' => 'bg-green-50 text-green-600',
            'Email' => 'bg-indigo-50 text-indigo-600',
            'Appel' => 'bg-amber-50 text-amber-600',
            'Réunion' => 'bg-purple-50 text-purple-600',
            default => 'bg-slate-50 text-slate-600',
        };
    }

    /**
     * Get the badge class for the interaction type.
     */
    public function getTypeBadgeClass(): string
    {
        return match ($this->interaction->type) {
            'Email envoyé' => 'badge-info',
            'Email reçu' => 'badge-success',
            'Email' => 'badge-primary',
            'Appel' => 'badge-warning',
            'Réunion' => 'badge-secondary',
            default => 'badge-outline',
        };
    }

    /**
     * Check if the interaction has an attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->interaction->piece_jointe);
    }

    /**
     * Get the relative time display.
     */
    public function getRelativeTime(): string
    {
        $date = $this->interaction->date_interaction;
        $now = now();

        if ($date->isToday()) {
            return "Aujourd'hui";
        } elseif ($date->isYesterday()) {
            return "Hier";
        } elseif ($date->diffInDays($now) <= 7) {
            return $date->format('l'); // Nom du jour
        } elseif ($date->year === $now->year) {
            return $date->format('j M'); // 15 Jan
        } else {
            return $date->format('j M Y'); // 15 Jan 2024
        }
    }
}
