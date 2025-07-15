<!-- Section commentaires -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-900">
                Commentaires
                @if($investor->commentaires->count() > 0)
                    <span class="text-sm font-normal text-slate-500">({{ $investor->commentaires_count }})</span>
                @endif
            </h3>
            <button onclick="openCommentModal()" class="btn btn-outline btn-xs">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter
            </button>
        </div>
    </div>

    <div class="p-6 max-h-96 overflow-y-auto">
        @if($investor->commentaires->count() > 0)
            <div class="space-y-4" id="comments-container">
                @foreach($investor->commentaires->take(5) as $commentaire)
                    <x-investor.comment-item :comment="$commentaire" />
                @endforeach
            </div>

            @if($investor->commentaires->count() > 5)
                <div class="mt-4 pt-4 border-t border-slate-200 text-center">
                    <button onclick="loadMoreComments()" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir {{ $investor->commentaires->count() - 5 }} commentaires supplémentaires
                    </button>
                </div>
            @endif
        @else
            <div class="text-center py-6">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <p class="text-sm text-slate-500 mb-3">Aucun commentaire</p>
                <button onclick="openCommentModal()" class="btn btn-outline btn-xs">
                    Ajouter le premier commentaire
                </button>
            </div>
        @endif
    </div>
</div>
