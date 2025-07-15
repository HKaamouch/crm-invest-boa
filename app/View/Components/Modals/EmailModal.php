<?php

namespace App\View\Components\Modals;

use App\Models\Investor;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmailModal extends Component
{
    public Investor $investor;

    /**
     * Create a new component instance.
     */
    public function __construct(Investor $investor)
    {
        $this->investor = $investor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.email-modal');
    }

    /**
     * Get the suggested subject line.
     */
    public function getSuggestedSubject(): string
    {
        return "Communication BOA - " . now()->format('M Y');
    }
}
