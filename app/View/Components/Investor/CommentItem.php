<?php

namespace App\View\Components\Investor;

use App\Models\InvestisseurCommentaire;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentItem extends Component
{
    public InvestisseurCommentaire $comment;

    /**
     * Create a new component instance.
     */
    public function __construct(InvestisseurCommentaire $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.investor.comment-item');
    }

    /**
     * Check if the current user can edit this comment.
     */
    public function canEdit(): bool
    {
        return $this->comment->user_id === auth()->id() || auth()->user()->hasRole('Administrateur');
    }

    /**
     * Get the relative time display.
     */
    public function getRelativeTime(): string
    {
        $date = $this->comment->created_at;
        $now = now();

        if ($date->isToday()) {
            return $date->format('H:i');
        } elseif ($date->isYesterday()) {
            return "Hier " . $date->format('H:i');
        } elseif ($date->diffInDays($now) <= 7) {
            return $date->format('D H:i'); // Lun 14:30
        } elseif ($date->year === $now->year) {
            return $date->format('j M H:i'); // 15 Jan 14:30
        } else {
            return $date->format('j M Y'); // 15 Jan 2024
        }
    }

    /**
     * Get the icon for comment type.
     */
    public function getTypeIcon(): string
    {
        return $this->comment->prive
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>';
    }

    /**
     * Get the color class for comment type.
     */
    public function getTypeColorClass(): string
    {
        return $this->comment->prive
            ? 'text-amber-600 bg-amber-50'
            : 'text-blue-600 bg-blue-50';
    }
}
