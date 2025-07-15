<!-- Modal commentaire -->
<dialog id="comment-modal" class="modal">
    <div class="modal-box w-11/12 max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg" id="comment-modal-title">Nouveau commentaire</h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="closeCommentModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="comment-form">
            @csrf
            <input type="hidden" id="comment-id" name="comment_id" value="">
            <input type="hidden" id="comment-method" name="_method" value="POST">

            <!-- Commentaire -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Commentaire <span class="text-red-500">*</span></span>
                </label>
                <textarea name="commentaire"
                          id="comment-text"
                          rows="5"
                          class="textarea textarea-bordered w-full"
                          placeholder="Ajoutez votre commentaire sur {{ $investor->nom_complet }}..."
                          required></textarea>
                <div class="label">
                    <span class="label-text-alt text-slate-500">Maximum 1000 caractères</span>
                </div>
            </div>

            <!-- Option privé -->
            <div class="form-control mb-6">
                <label class="label cursor-pointer justify-start">
                    <input type="checkbox" name="prive" id="comment-private" class="checkbox checkbox-primary mr-3">
                    <div>
                        <span class="label-text font-semibold">Commentaire privé</span>
                        <div class="text-sm text-slate-500">Visible uniquement par vous et les administrateurs</div>
                    </div>
                </label>
            </div>

            <!-- Boutons d'action -->
            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="closeCommentModal()">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary" id="save-comment-btn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="save-comment-text">Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openCommentModal() {
        // Reset pour nouveau commentaire
        document.getElementById('comment-modal-title').textContent = 'Nouveau commentaire';
        document.getElementById('comment-id').value = '';
        document.getElementById('comment-method').value = 'POST';
        document.getElementById('save-comment-text').textContent = 'Enregistrer';
        document.getElementById('comment-form').reset();

        document.getElementById('comment-modal').showModal();
    }

    function editComment(commentId) {
        // Récupérer les données du commentaire
        const commentElement = document.getElementById(`comment-${commentId}`);
        if (!commentElement) return;

        // Extraire le texte du commentaire et l'état privé
        const commentText = commentElement.querySelector('.text-sm.text-slate-700').textContent.trim();
        const isPrivate = commentElement.querySelector('.text-amber-600') !== null;

        // Remplir le formulaire
        document.getElementById('comment-modal-title').textContent = 'Modifier le commentaire';
        document.getElementById('comment-id').value = commentId;
        document.getElementById('comment-method').value = 'PATCH';
        document.getElementById('comment-text').value = commentText;
        document.getElementById('comment-private').checked = isPrivate;
        document.getElementById('save-comment-text').textContent = 'Mettre à jour';

        document.getElementById('comment-modal').showModal();
    }

    function closeCommentModal() {
        document.getElementById('comment-modal').close();
        document.getElementById('comment-form').reset();
    }

    async function deleteComment(commentId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            return;
        }

        try {
            const response = await fetch(`{{ url('/ajax/comments') }}/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Supprimer l'élément du DOM
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }

                showToast('Commentaire supprimé avec succès', 'success');
            } else {
                showToast(result.message || 'Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de la suppression', 'error');
        }
    }

    // Gestionnaire de soumission du formulaire
    document.getElementById('comment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btn = document.getElementById('save-comment-btn');
        const originalText = btn.innerHTML;

        // Désactiver le bouton et montrer le loading
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner loading-sm mr-2"></span>Enregistrement...';

        try {
            const formData = new FormData(this);
            const commentId = document.getElementById('comment-id').value;
            const method = document.getElementById('comment-method').value;

            let url, httpMethod;
            if (method === 'PATCH') {
                // Modification
                url = `{{ url('/ajax/comments') }}/${commentId}`;
                httpMethod = 'PATCH';
            } else {
                // Création
                url = `{{ route('ajax.investor.comments.store', $investor) }}`;
                httpMethod = 'POST';
            }

            const response = await fetch(url, {
                method: httpMethod,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                // Fermer la modal
                closeCommentModal();

                if (method === 'PATCH') {
                    // Mise à jour du commentaire existant
                    updateCommentInList(commentId, result.commentaire);
                    showToast('Commentaire mis à jour avec succès', 'success');
                } else {
                    // Ajout du nouveau commentaire
                    addCommentToList(result.commentaire);
                    showToast('Commentaire ajouté avec succès', 'success');
                }
            } else {
                showToast(result.message || 'Erreur lors de l\'enregistrement', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de l\'enregistrement', 'error');
        } finally {
            // Réactiver le bouton
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });

    // Fonction pour ajouter un commentaire à la liste
    function addCommentToList(comment) {
        const container = document.getElementById('comments-container');
        if (container) {
            const commentHtml = createCommentItem(comment);
            container.insertAdjacentHTML('afterbegin', commentHtml);

            // Si c'était vide, supprimer le message "aucun commentaire"
            const emptyMessage = container.parentElement.querySelector('.text-center.py-6');
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }

    // Fonction pour mettre à jour un commentaire dans la liste
    function updateCommentInList(commentId, comment) {
        const commentElement = document.getElementById(`comment-${commentId}`);
        if (commentElement) {
            const newCommentHtml = createCommentItem(comment);
            commentElement.outerHTML = newCommentHtml;
        }
    }

    // Fonction pour créer le HTML d'un commentaire
    function createCommentItem(comment) {
        const privateIcon = comment.prive ?
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>';

        const colorClass = comment.prive ? 'text-amber-600 bg-amber-50' : 'text-blue-600 bg-blue-50';

        return `
        <div class="flex items-start space-x-3 py-3 border-b border-slate-100 last:border-b-0" id="comment-${comment.id}">
            <div class="flex-shrink-0">
                <img class="w-8 h-8 rounded-full"
                     src="https://ui-avatars.com/api/?name=${encodeURIComponent(comment.auteur_nom)}&size=32&background=e0e7ff&color=2563eb"
                     alt="${comment.auteur_nom}">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <p class="text-sm font-medium text-slate-900">${comment.auteur_nom}</p>
                        <div class="flex items-center space-x-1">
                            <div class="w-4 h-4 ${colorClass} rounded-full flex items-center justify-center">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${privateIcon}
                                </svg>
                            </div>
                            ${comment.prive ? '<span class="text-xs text-amber-600 font-medium">Privé</span>' : ''}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <time class="text-xs text-slate-500">Maintenant</time>
                        ${comment.can_edit ? `
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </div>
                                <ul tabindex="0" class="dropdown-content menu bg-white rounded-box z-50 w-40 p-2 shadow-xl border border-slate-200">
                                    <li><button onclick="editComment(${comment.id})" class="text-sm">Modifier</button></li>
                                    <li><button onclick="deleteComment(${comment.id})" class="text-sm text-red-600">Supprimer</button></li>
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="text-sm text-slate-700 leading-relaxed">
                    ${comment.commentaire}
                </div>
            </div>
        </div>
    `;
    }
</script>
