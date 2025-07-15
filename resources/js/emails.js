// Gestion des emails - Autocomplete et composition
class EmailComposer {
    constructor() {
        this.selectedRecipients = {
            to: new Map(),
            cc: new Map(),
            cci: new Map()
        };

        this.searchTimeout = null;
        this.currentInput = null;
        this.currentSuggestions = null;

        this.init();
    }

    init() {
        this.setupAutocomplete('to');
        this.setupAutocomplete('cc');
        this.setupAutocomplete('cci');
        this.setupForm();
        this.setupKeyboardNavigation();
    }

    setupAutocomplete(type) {
        const input = document.getElementById(`${type}-input`);
        const suggestions = document.getElementById(`${type}-suggestions`);

        if (!input || !suggestions) return;

        input.addEventListener('input', (e) => this.handleInput(e, type));
        input.addEventListener('focus', (e) => this.handleFocus(e, type));
        input.addEventListener('blur', (e) => this.handleBlur(e, type));
        input.addEventListener('keydown', (e) => this.handleKeydown(e, type));
    }

    handleInput(e, type) {
        const query = e.target.value.trim();

        clearTimeout(this.searchTimeout);

        if (query.length < 2) {
            this.hideSuggestions(type);
            return;
        }

        this.searchTimeout = setTimeout(() => {
            this.searchInvestors(query, type);
        }, 300);
    }

    handleFocus(e, type) {
        this.currentInput = e.target;
        this.currentSuggestions = document.getElementById(`${type}-suggestions`);
    }

    handleBlur(e, type) {
        // Délai pour permettre le clic sur les suggestions
        setTimeout(() => {
            if (!this.currentSuggestions?.matches(':hover')) {
                this.hideSuggestions(type);
            }
        }, 200);
    }

    handleKeydown(e, type) {
        const suggestions = document.getElementById(`${type}-suggestions`);
        const items = suggestions.querySelectorAll('.suggestion-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.navigateSuggestions(items, 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.navigateSuggestions(items, -1);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            const selected = suggestions.querySelector('.suggestion-item.selected');
            if (selected) {
                this.selectInvestor(selected.dataset.investor, type);
            }
        } else if (e.key === 'Escape') {
            this.hideSuggestions(type);
        }
    }

    navigateSuggestions(items, direction) {
        const current = document.querySelector('.suggestion-item.selected');
        let index = current ? Array.from(items).indexOf(current) : -1;

        if (current) {
            current.classList.remove('selected', 'bg-blue-50');
        }

        index = Math.max(0, Math.min(items.length - 1, index + direction));

        if (items[index]) {
            items[index].classList.add('selected', 'bg-blue-50');
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    async searchInvestors(query, type) {
        try {
            const response = await fetch(`/emails/search-investors?q=${encodeURIComponent(query)}`);
            const investors = await response.json();

            this.displaySuggestions(investors, type);
        } catch (error) {
            console.error('Erreur lors de la recherche:', error);
        }
    }

    displaySuggestions(investors, type) {
        const suggestions = document.getElementById(`${type}-suggestions`);
        const template = document.getElementById('suggestion-template');

        suggestions.innerHTML = '';

        if (investors.length === 0) {
            suggestions.innerHTML = '<div class="p-3 text-sm text-slate-500">Aucun investisseur trouvé</div>';
            suggestions.classList.remove('hidden');
            return;
        }

        investors.forEach(investor => {
            // Vérifier si l'investisseur n'est pas déjà sélectionné
            if (this.isInvestorSelected(investor.id)) {
                return;
            }

            const item = template.content.cloneNode(true);
            const container = item.querySelector('.suggestion-item');

            container.dataset.investor = JSON.stringify(investor);
            container.addEventListener('click', () => this.selectInvestor(investor, type));

            item.querySelector('.suggestion-avatar').src = investor.avatar_url;
            item.querySelector('.suggestion-name').textContent = investor.nom_complet;
            item.querySelector('.suggestion-email').textContent = investor.email;
            item.querySelector('.suggestion-details').textContent = investor.fonction || 'Fonction non précisée';
            item.querySelector('.suggestion-country').textContent = investor.pays || '';

            suggestions.appendChild(item);
        });

        suggestions.classList.remove('hidden');
    }

    selectInvestor(investor, type) {
        // Convertir en objet si c'est une chaîne JSON
        if (typeof investor === 'string') {
            investor = JSON.parse(investor);
        }

        // Vérifier si l'investisseur n'est pas déjà sélectionné
        if (this.isInvestorSelected(investor.id)) {
            this.showToast('Cet investisseur est déjà sélectionné', 'warning');
            return;
        }

        // Ajouter à la liste des sélectionnés
        this.selectedRecipients[type].set(investor.id, investor);

        // Créer le tag visuel
        this.createRecipientTag(investor, type);

        // Vider l'input et masquer les suggestions
        document.getElementById(`${type}-input`).value = '';
        this.hideSuggestions(type);

        // Mettre à jour le compteur
        this.updateRecipientCount();
    }

    createRecipientTag(investor, type) {
        const container = document.getElementById(`${type}-container`);
        const input = document.getElementById(`${type}-input`);
        const template = document.getElementById('recipient-tag-template');

        const tag = template.content.cloneNode(true);
        const tagDiv = tag.querySelector('div');

        tagDiv.dataset.investorId = investor.id;
        tagDiv.dataset.type = type;

        tag.querySelector('.recipient-avatar').src = investor.avatar_url;
        tag.querySelector('.recipient-name').textContent = investor.nom_complet;
        tag.querySelector('.recipient-id').name = `${type}[]`;
        tag.querySelector('.recipient-id').value = investor.id;

        // Insérer avant l'input
        container.insertBefore(tag, input);

        // Animation d'apparition
        tagDiv.classList.add('recipient-tag');
    }

    isInvestorSelected(investorId) {
        return this.selectedRecipients.to.has(investorId) ||
            this.selectedRecipients.cc.has(investorId) ||
            this.selectedRecipients.cci.has(investorId);
    }

    hideSuggestions(type) {
        const suggestions = document.getElementById(`${type}-suggestions`);
        suggestions.classList.add('hidden');

        // Nettoyer la sélection
        suggestions.querySelectorAll('.suggestion-item.selected')
            .forEach(item => item.classList.remove('selected', 'bg-blue-50'));
    }

    updateRecipientCount() {
        const total = this.selectedRecipients.to.size +
            this.selectedRecipients.cc.size +
            this.selectedRecipients.cci.size;

        document.getElementById('recipient-count').textContent = total;
    }

    setupForm() {
        const form = document.getElementById('compose-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        } else {
            console.error('Le formulaire de composition n\'a pas été trouvé');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.validateForm()) {
            return;
        }

        const formData = new FormData(e.target);
        const sendButton = document.getElementById('send-button');

        // Désactiver le bouton et afficher le loading
        sendButton.disabled = true;
        sendButton.innerHTML = `
            <span class="loading loading-spinner loading-sm mr-2"></span>
            Envoi en cours...
        `;

        try {
            const response = await fetch('/emails/send', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showToast(result.message, 'success');
                this.resetForm();
                closeComposeModal();

                // Recharger la page pour voir les nouveaux emails
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showToast(result.message, 'error');
            }
        } catch (error) {
            console.error('Erreur lors de l\'envoi:', error);
            this.showToast('Erreur lors de l\'envoi de l\'email', 'error');
        } finally {
            // Réactiver le bouton
            sendButton.disabled = false;
            sendButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Envoyer
            `;
        }
    }

    validateForm() {
        let isValid = true;

        // Vérifier les destinataires
        if (this.selectedRecipients.to.size === 0) {
            this.showFieldError('to-error', 'Au moins un destinataire est requis');
            isValid = false;
        } else {
            this.hideFieldError('to-error');
        }

        // Vérifier l'objet
        const objet = document.getElementById('objet').value.trim();
        if (!objet) {
            this.showFieldError('objet-error', 'L\'objet est obligatoire');
            isValid = false;
        } else {
            this.hideFieldError('objet-error');
        }

        // Vérifier le message
        const message = document.getElementById('message').value.trim();
        if (!message) {
            this.showFieldError('message-error', 'Le message est obligatoire');
            isValid = false;
        } else {
            this.hideFieldError('message-error');
        }

        return isValid;
    }

    showFieldError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    hideFieldError(elementId) {
        const errorElement = document.getElementById(elementId);
        errorElement.classList.add('hidden');
    }

    resetForm() {
        // Réinitialiser les sélections
        this.selectedRecipients.to.clear();
        this.selectedRecipients.cc.clear();
        this.selectedRecipients.cci.clear();

        // Supprimer tous les tags
        ['to', 'cc', 'cci'].forEach(type => {
            const container = document.getElementById(`${type}-container`);
            const tags = container.querySelectorAll('.recipient-tag, [data-investor-id]');
            tags.forEach(tag => tag.remove());
        });

        // Réinitialiser le formulaire
        document.getElementById('compose-form').reset();

        // Mettre à jour le compteur
        this.updateRecipientCount();

        // Masquer les sections CC et CCI
        document.getElementById('cc-section').classList.add('hidden');
        document.getElementById('cci-section').classList.add('hidden');
    }

    showToast(message, type = 'info') {
        // Créer ou utiliser un système de toast existant
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'error' : type === 'success' ? 'success' : 'info'} fixed top-4 right-4 z-50 max-w-md shadow-lg`;
        toast.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

// Fonctions globales pour les modales - Exposées dans window pour être accessibles via les attributs onclick
window.openComposeModal = function() {
    const modal = document.getElementById('compose-modal');
    if (modal) {
        modal.showModal();
    } else {
        console.error('Modal de composition non trouvée');
    }
};

window.closeComposeModal = function() {
    const modal = document.getElementById('compose-modal');
    if (modal) {
        if (window.emailComposer) {
            window.emailComposer.resetForm();
        }
        modal.close();
    }
};

window.toggleCcBcc = function(type) {
    const section = document.getElementById(`${type}-section`);
    if (section) {
        section.classList.toggle('hidden');

        if (!section.classList.contains('hidden')) {
            document.getElementById(`${type}-input`).focus();
        }
    }
};

window.removeRecipient = function(button) {
    if (!window.emailComposer) return;

    const tag = button.closest('[data-investor-id]');
    const investorId = parseInt(tag.dataset.investorId);
    const type = tag.dataset.type;

    // Supprimer de la liste des sélectionnés
    window.emailComposer.selectedRecipients[type].delete(investorId);

    // Supprimer le tag visuellement
    tag.remove();

    // Mettre à jour le compteur
    window.emailComposer.updateRecipientCount();
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Exposer l'instance dans window pour y accéder depuis les fonctions globales
        window.emailComposer = new EmailComposer();
        console.log('EmailComposer initialisé avec succès');

        // Vérification de la présence du modal
        const modal = document.getElementById('compose-modal');
        if (!modal) {
            console.error('Modal de composition non trouvée dans le DOM');
        }

        // Gestionnaire pour fermer les modales avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.querySelector('dialog[open]');
                if (modal) {
                    modal.close();
                }
            }
        });
    } catch (error) {
        console.error('Erreur lors de l\'initialisation de EmailComposer:', error);
    }
});

// Export pour utilisation dans d'autres scripts si nécessaire
window.EmailComposer = EmailComposer;
