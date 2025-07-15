<div class="flex items-start space-x-3 py-3 border-b border-slate-100 last:border-b-0" id="comment-{{ $comment->id }}">
    <!-- Author Avatar -->
    <div class="flex-shrink-0">
        <img class="w-8 h-8 rounded-full"
             src="https://ui-avatars.com/api/?name={{ urlencode($comment->auteur_nom) }}&size=32&background=e0e7ff&color=2563eb"
             alt="{{ $comment->auteur_nom }}">
    </div>

    <!-- Comment Content -->
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-2">
                <p class="text-sm font-medium text-slate-900">{{ $comment->auteur_nom }}</p>
                <div class="flex items-center space-x-1">
                    <div class="w-4 h-4 {{ $getTypeColorClass() }} rounded-full flex items-center justify-center">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $getTypeIcon() !!}
                        </svg>
                    </div>
                    @if($comment->prive)
                        <span class="text-xs text-amber-600 font-medium">Privé</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <time class="text-xs text-slate-500" datetime="{{ $comment->created_at->toISOString() }}">
                    {{ $getRelativeTime() }}
                </time>
                @if($canEdit())
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </div>
                        <ul tabindex="0" class="dropdown-content menu bg-white rounded-box z-50 w-40 p-2 shadow-xl border border-slate-200">
                            <li><button onclick="editComment({{ $comment->id }})" class="text-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Modifier
                                </button></li>
                            <li><button onclick="deleteComment({{ $comment->id }})" class="text-sm text-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button></li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="text-sm text-slate-700 leading-relaxed">
            {{ $comment->commentaire }}
        </div>
    </div>
</div>
