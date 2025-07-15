<?php

namespace App\View\Components\Modals;

use App\Models\Investor;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InteractionModal extends Component
{
    public Investor $investor;
    public array $interactionTypes;

    /**
     * Create a new component instance.
     */
    public function __construct(Investor $investor)
    {
        $this->investor = $investor;
        $this->interactionTypes = [
            'Email' => 'Email',
            'Appel' => 'Appel téléphonique',
            'Réunion' => 'Réunion/Rendez-vous',
            'Email envoyé' => 'Email envoyé',
            'Email reçu' => 'Email reçu',
            'Autre' => 'Autre type d\'interaction'
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.interaction-modal');
    }

    /**
     * Get today's date formatted for input.
     */
    public function getTodayDate(): string
    {
        return now()->format('Y-m-d');
    }
}
