// resources/js/investor-show.js
// JavaScript pour la page de détail d'un investisseur

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des fonctionnalités
    initializeInteractions();
    initializeComments();
    initializeModals();
});

// Gestion des interactions
function initializeInteractions() {
    // Actualiser les interactions périodiquement (optionnel)
    // setInterval(refreshInteractions, 30000); // toutes les 30 secondes
}

// Gestion des commentaires
function initializeComments() {
    // Charger plus de commentaires
    window.loadMoreComments = function() {
        // Implémentation pour charger plus de commentaires
        console.log('Loading more comments...');
    };
}

// Gestion des modales
function initializeModals() {
    // Fermer les modales avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

// Fonctions globales pour les modales
window.openEmailModal = function() {
    document.getElementById('email-modal').showModal();
};

window.closeEmailModal = function() {
    document.getElementById('email-modal').close();
    document.getElementById('email-form').reset();
};

window.openInteractionModal = function() {
    document.getElementById('interaction-modal').showModal();
};

window.closeInteractionModal = function() {
    document.getElementById('interaction-modal').close();
    document.getElementById('interaction-form').reset();
    // Remettre la date à aujourd'hui
    const dateInput = document.getElementById('interaction-date');
    if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
};

window.openCommentModal = function() {
    // Reset pour nouveau commentaire
    document.getElementById('comment-modal-title').textContent = 'Nouveau commentaire';
    document.getElementById('comment-id').value = '';
    document.getElementById('comment-method').value = 'POST';
    document.getElementById('save-comment-text').textContent = 'Enregistrer';
    document.getElementById('comment-form').reset();

    document.getElementById('comment-modal').showModal();
};

window.closeCommentModal = function() {
    document.getElementById('comment-modal').close();
    document.getElementById('comment-form').reset();
};

// Fermer toutes les modales
function closeAllModals() {
    const modals = document.querySelectorAll('dialog[open]');
    modals.forEach(modal => modal.close());
}

// Fonction pour rafraîchir les interactions
window.refreshInteractions = async function() {
    try {
        const investorId = window.location.pathname.split('/').pop();
        const response = await fetch(`/ajax/investors/${investorId}/interactions`);
        const result = await response.json();

        if (result.success) {
            updateInteractionsList(result.interactions);
        }
    } catch (error) {
        console.error('Erreur lors du rafraîchissement des interactions:', error);
    }
};

// Mettre à jour la liste des interactions
function updateInteractionsList(interactions) {
    const container = document.getElementById('interactions-container');
    if (!container) return;

    // Vider le conteneur
    container.innerHTML = '';

    if (interactions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-slate-500 mb-4">Aucune interaction enregistrée</p>
                <button onclick="openInteractionModal()" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter la première interaction
                </button>
            </div>
        `;
        return;
    }

    // Ajouter chaque interaction
    interactions.forEach(interaction => {
        const interactionHtml = createInteractionCard(interaction);
        container.insertAdjacentHTML('beforeend', interactionHtml);
    });
}

// Créer le HTML d'une carte d'interaction
function createInteractionCard(interaction) {
    const typeColors = {
        'Email envoyé': 'bg-blue-50 text-blue-600',
        'Email reçu': 'bg-green-50 text-green-600',
        'Email': 'bg-indigo-50 text-indigo-600',
        'Appel': 'bg-amber-50 text-amber-600',
        'Réunion': 'bg-purple-50 text-purple-600'
    };

    const typeBadges = {
        'Email envoyé': 'badge-info',
        'Email reçu': 'badge-success',
        'Email': 'badge-primary',
        'Appel': 'badge-warning',
        'Réunion': 'badge-secondary'
    };

    const typeIcons = {
        'Email': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
        'Email envoyé': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
        'Email reçu': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
        'Appel': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>',
        'Réunion': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>'
    };

    const colorClass = typeColors[interaction.type] || 'bg-slate-50 text-slate-600';
    const badgeClass = typeBadges[interaction.type] || 'badge-outline';
    const icon = typeIcons[interaction.type] || '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';

    const attachmentHtml = interaction.piece_jointe ? `
        <div class="mt-2">
            <a href="/investors/interactions/${interaction.id}/download"
               class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Télécharger la pièce jointe
            </a>
        </div>
    ` : '';

    const expandButton = interaction.description.length > interaction.description_courte.length ? `
        <button onclick="toggleDescription(${interaction.id})"
                class="text-xs text-blue-600 hover:text-blue-800">
            Voir plus
        </button>
    ` : '';

    const fullDescription = interaction.description.length > interaction.description_courte.length ? `
        <div id="full-description-${interaction.id}" class="hidden mt-3 text-sm text-slate-700 bg-slate-50 p-3 rounded">
            ${interaction.description}
            <button onclick="toggleDescription(${interaction.id})"
                    class="block mt-2 text-xs text-blue-600 hover:text-blue-800">
                Voir moins
            </button>
        </div>
    ` : '';

    return `
        <div class="flex items-start space-x-4 p-4 hover:bg-slate-50 rounded-lg transition-colors">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="badge ${badgeClass} badge-sm">${interaction.type}</span>
                        <span class="text-sm text-slate-500">${interaction.date_interaction_formatee}</span>
                        ${interaction.piece_jointe ? '<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Pièce jointe"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>' : ''}
                    </div>
                    <time class="text-xs text-slate-400" datetime="${interaction.created_at}">
                        ${new Date(interaction.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}
                    </time>
                </div>

                <div class="text-sm text-slate-900 mb-2">
                    ${interaction.description_courte}
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center text-xs text-slate-500">
                        <img class="w-5 h-5 rounded-full mr-2"
                             src="https://ui-avatars.com/api/?name=${encodeURIComponent(interaction.user_name)}&size=20&background=e0e7ff&color=2563eb"
                             alt="${interaction.user_name}">
                        <span>${interaction.user_name}</span>
                    </div>
                    ${expandButton}
                </div>

                ${fullDescription}
                ${attachmentHtml}
            </div>
        </div>
    `;
}

// Fonction pour basculer l'affichage de la description complète
window.toggleDescription = function(interactionId) {
    const fullDesc = document.getElementById(`full-description-${interactionId}`);
    if (fullDesc) {
        fullDesc.classList.toggle('hidden');
    }
};

// Fonction pour confirmer la suppression de l'investisseur
window.confirmDelete = function() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet investisseur ? Cette action est irréversible.')) {
        // Créer et soumettre un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        // Token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        // Method DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
};

// Fonction utilitaire pour afficher les toasts
window.showToast = function(message, type = 'info') {
    // Supprimer les anciens toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());

    // Créer l'élément toast
    const toast = document.createElement('div');
    toast.className = `toast-notification alert alert-${type} fixed top-4 right-4 w-auto max-w-sm z-50 animate-fade-in-scale shadow-lg`;

    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />',
        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
    };

    toast.innerHTML = `
        <svg class="w-6 h-6 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
            ${icons[type] || icons.info}
        </svg>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="btn btn-ghost btn-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    document.body.appendChild(toast);

    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
};

// Fonction pour gérer le chargement des commentaires supplémentaires
window.loadMoreComments = async function() {
    try {
        const investorId = window.location.pathname.split('/').pop();
        const response = await fetch(`/ajax/investors/${investorId}/comments?per_page=20`);
        const result = await response.json();

        if (result.success) {
            updateCommentsList(result.commentaires);

            // Masquer le bouton "Voir plus" si tous les commentaires sont chargés
            const loadMoreBtn = document.querySelector('[onclick="loadMoreComments()"]');
            if (loadMoreBtn && result.commentaires.length < 20) {
                loadMoreBtn.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement des commentaires:', error);
        showToast('Erreur lors du chargement des commentaires', 'error');
    }
};

// Mettre à jour la liste des commentaires
function updateCommentsList(comments) {
    const container = document.getElementById('comments-container');
    if (!container) return;

    // Vider le conteneur
    container.innerHTML = '';

    if (comments.length === 0) {
        const emptyState = `
            <div class="text-center py-6">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <p class="text-sm text-slate-500 mb-3">Aucun commentaire</p>
                <button onclick="openCommentModal()" class="btn btn-outline btn-xs">
                    Ajouter le premier commentaire
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', emptyState);
        return;
    }

    // Ajouter chaque commentaire
    comments.forEach(comment => {
        const commentHtml = createCommentItem(comment);
        container.insertAdjacentHTML('beforeend', commentHtml);
    });
}

// Créer le HTML d'un commentaire
function createCommentItem(comment) {
    const privateIcon = comment.prive ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>';

    const colorClass = comment.prive ? 'text-amber-600 bg-amber-50' : 'text-blue-600 bg-blue-50';

    const actionsMenu = comment.can_edit ? `
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-white rounded-box z-50 w-40 p-2 shadow-xl border border-slate-200">
                <li><button onclick="editComment(${comment.id})" class="text-sm">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </button></li>
                <li><button onclick="deleteComment(${comment.id})" class="text-sm text-red-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button></li>
            </ul>
        </div>
    ` : '';

    const relativeTime = getRelativeTime(comment.created_at);

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
                        <time class="text-xs text-slate-500" datetime="${comment.created_at}">
                            ${relativeTime}
                        </time>
                        ${actionsMenu}
                    </div>
                </div>
                <div class="text-sm text-slate-700 leading-relaxed">
                    ${comment.commentaire}
                </div>
            </div>
        </div>
    `;
}

// Fonction utilitaire pour calculer le temps relatif
function getRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));

    if (diffInMinutes < 1) {
        return 'Maintenant';
    } else if (diffInMinutes < 60) {
        return `Il y a ${diffInMinutes} min`;
    } else if (diffInMinutes < 1440) { // 24h
        const hours = Math.floor(diffInMinutes / 60);
        return `Il y a ${hours}h`;
    } else if (diffInMinutes < 10080) { // 7 jours
        const days = Math.floor(diffInMinutes / 1440);
        return `Il y a ${days}j`;
    } else {
        return date.toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'short',
            year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
        });
    }
}

// Gestion des erreurs globales
window.addEventListener('error', function(e) {
    console.error('Erreur JavaScript:', e.error);
});

// Gestion des erreurs de réseau
window.addEventListener('unhandledrejection', function(e) {
    console.error('Promesse rejetée:', e.reason);
    if (e.reason && e.reason.message && e.reason.message.includes('fetch')) {
        showToast('Erreur de connexion au serveur', 'error');
    }
});

// Export des fonctions pour les tests
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        createInteractionCard,
        createCommentItem,
        getRelativeTime,
        showToast
    };
}
