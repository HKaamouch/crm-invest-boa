<?php

namespace App\View\Components\Investor;

use App\Models\Organisation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OrganisationCard extends Component
{
    public Organisation $organisation;
    public object $pivot;



    /**
     * Create a new component instance.
     */
    public function __construct(Organisation $organisation, object $pivot)
    {
        $this->organisation = $organisation;
        $this->pivot = $pivot;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.investor.organisation-card');
    }

    /**
     * Get the period display text.
     */
    public function getPeriodText(): string
    {
        $debut = $this->pivot->date_debut ? \Carbon\Carbon::parse($this->pivot->date_debut)->format('m/Y') : null;
        $fin = $this->pivot->date_fin ? \Carbon\Carbon::parse($this->pivot->date_fin)->format('m/Y') : null;

        if ($debut && $fin) {
            return "De {$debut} à {$fin}";
        } elseif ($debut) {
            return $this->pivot->actuel ? "Depuis {$debut}" : "À partir de {$debut}";
        } elseif ($fin) {
            return "Jusqu'à {$fin}";
        }

        return $this->pivot->actuel ? 'Période actuelle' : 'Période non précisée';
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClass(): string
    {
        return $this->pivot->actuel ? 'badge-success' : 'badge-outline';
    }

    /**
     * Get the status text.
     */
    public function getStatusText(): string
    {
        return $this->pivot->actuel ? 'Actuel' : 'Ancien';
    }
}
