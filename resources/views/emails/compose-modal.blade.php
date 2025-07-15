<!-- Modal de composition d'email -->
<dialog id="compose-modal" class="modal">
    <div class="modal-box w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6 sticky top-0 bg-base-100 pb-4 border-b border-slate-200">
            <h3 class="font-bold text-xl text-slate-800">Composer un email</h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost close-modal-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="compose-form" enctype="multipart/form-data">
            @csrf

            <!-- Destinataires -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Destinataires <span class="text-red-500">*</span></span>
                    <span class="label-text-alt text-slate-500">Tapez le nom ou l'email pour rechercher</span>
                </label>
                <div class="recipient-input-container relative">
                    <div class="flex flex-wrap gap-2 p-3 border border-slate-300 rounded-lg bg-white min-h-[3rem] focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200" id="to-container">
                        <input type="text"
                               id="to-input"
                               class="flex-1 min-w-[200px] border-0 outline-0 bg-transparent"
                               placeholder="Rechercher des investisseurs..."
                               autocomplete="off">
                    </div>
                    <div id="to-suggestions" class="absolute z-50 w-full bg-white border border-slate-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto top-full mt-1"></div>
                </div>
                <div class="text-sm text-red-500 hidden" id="to-error">Au moins un destinataire est requis</div>
            </div>

            <!-- CC -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">CC (Copie conforme)</span>
                    <button type="button" class="label-text-alt link link-primary text-xs" onclick="toggleCcBcc('cc')">
                        Afficher/Masquer CC
                    </button>
                </label>
                <div class="recipient-input-container relative hidden" id="cc-section">
                    <div class="flex flex-wrap gap-2 p-3 border border-slate-300 rounded-lg bg-white min-h-[3rem] focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200" id="cc-container">
                        <input type="text"
                               id="cc-input"
                               class="flex-1 min-w-[200px] border-0 outline-0 bg-transparent"
                               placeholder="Rechercher des investisseurs en copie..."
                               autocomplete="off">
                    </div>
                    <div id="cc-suggestions" class="absolute z-50 w-full bg-white border border-slate-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto top-full mt-1"></div>
                </div>
            </div>

            <!-- CCI -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">CCI (Copie conforme invisible)</span>
                    <button type="button" class="label-text-alt link link-primary text-xs" onclick="toggleCcBcc('cci')">
                        Afficher/Masquer CCI
                    </button>
                </label>
                <div class="recipient-input-container relative hidden" id="cci-section">
                    <div class="flex flex-wrap gap-2 p-3 border border-slate-300 rounded-lg bg-white min-h-[3rem] focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200" id="cci-container">
                        <input type="text"
                               id="cci-input"
                               class="flex-1 min-w-[200px] border-0 outline-0 bg-transparent"
                               placeholder="Rechercher des investisseurs en copie invisible..."
                               autocomplete="off">
                    </div>
                    <div id="cci-suggestions" class="absolute z-50 w-full bg-white border border-slate-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto top-full mt-1"></div>
                </div>
            </div>

            <!-- Objet -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Objet <span class="text-red-500">*</span></span>
                </label>
                <input type="text"
                       name="objet"
                       id="objet"
                       class="input input-bordered w-full"
                       placeholder="Objet de l'email..."
                       required>
                <div class="text-sm text-red-500 hidden" id="objet-error">L'objet est obligatoire</div>
            </div>

            <!-- Message -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-semibold">Message <span class="text-red-500">*</span></span>
                </label>
                <textarea name="message"
                          id="message"
                          class="textarea textarea-bordered w-full h-48"
                          placeholder="Votre message..."
                          required></textarea>
                <div class="text-sm text-red-500 hidden" id="message-error">Le message est obligatoire</div>
            </div>

            <!-- Pièces jointes -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-semibold">Pièces jointes</span>
                    <span class="label-text-alt text-slate-500">PDF, Word, Images (max 10 Mo par fichier)</span>
                </label>
                <input type="file"
                       name="pieces_jointes[]"
                       id="pieces_jointes"
                       class="file-input file-input-bordered w-full"
                       multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-slate-600">
                        <span id="recipient-count">0</span> destinataire(s) sélectionné(s)
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="button"
                            class="btn btn-outline close-modal-btn">
                        Annuler
                    </button>
                    <button type="submit"
                            class="btn btn-primary"
                            id="send-button">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Envoyer
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-backdrop"></div>
</dialog>

<!-- Template pour les tags de destinataires -->
<template id="recipient-tag-template">
    <div class="flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm recipient-tag">
        <img class="w-5 h-5 rounded-full mr-2 recipient-avatar" src="" alt="">
        <span class="recipient-name"></span>
        <span class="recipient-email text-blue-600 text-xs ml-1">(<span class="email-value"></span>)</span>
        <button type="button"
                class="ml-2 text-blue-600 hover:text-blue-800 remove-recipient"
                onclick="removeRecipient(this)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <input type="hidden" class="recipient-id" name="" value="">
        <input type="hidden" class="recipient-email-input" name="" value="">
    </div>
</template>

<!-- Template pour les suggestions -->
<template id="suggestion-template">
    <div class="suggestion-item flex items-center p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-b-0">
        <img class="w-10 h-10 rounded-full mr-3 suggestion-avatar" src="" alt="">
        <div class="flex-1">
            <div class="suggestion-name font-medium text-slate-800"></div>
            <div class="suggestion-email text-sm text-slate-600"></div>
            <div class="suggestion-details text-xs text-slate-500"></div>
        </div>
        <div class="text-xs text-slate-400 suggestion-country"></div>
    </div>
</template>

<style>
    .recipient-input-container input:focus {
        box-shadow: none;
        outline: none;
    }

    .suggestion-item:hover {
        background-color: #f8fafc;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Animation pour l'apparition des suggestions */
    #to-suggestions, #cc-suggestions, #cci-suggestions {
        transition: all 0.2s ease-in-out;
        transform-origin: top;
    }

    #to-suggestions.hidden, #cc-suggestions.hidden, #cci-suggestions.hidden {
        transform: scaleY(0);
        opacity: 0;
    }

    #to-suggestions:not(.hidden), #cc-suggestions:not(.hidden), #cci-suggestions:not(.hidden) {
        transform: scaleY(1);
        opacity: 1;
    }

    /* Style pour les tags de destinataires */
    .recipient-tag {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

<script>
    // Script pour gérer les boutons de fermeture directement dans le template
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer les boutons de fermeture du modal
        document.querySelectorAll('.close-modal-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.getElementById('compose-modal');
                if (modal) {
                    if (window.closeComposeModal) {
                        window.closeComposeModal();
                    } else {
                        modal.close();
                    }
                }
            });
        });

        // Gérer le clic sur le backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                const modal = document.getElementById('compose-modal');
                if (modal) {
                    if (window.closeComposeModal) {
                        window.closeComposeModal();
                    } else {
                        modal.close();
                    }
                }
            });
        }

        // Initialiser les boutons de toggle CC/CCI
        document.querySelectorAll('[onclick*="toggleCcBcc"]').forEach(btn => {
            const type = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function() {
                if (window.toggleCcBcc) {
                    window.toggleCcBcc(type);
                } else {
                    const section = document.getElementById(`${type}-section`);
                    if (section) {
                        section.classList.toggle('hidden');
                        if (!section.classList.contains('hidden')) {
                            document.getElementById(`${type}-input`).focus();
                        }
                    }
                }
            });
        });

        // Initialiser les boutons de suppression de destinataires
        document.querySelectorAll('[onclick*="removeRecipient"]').forEach(btn => {
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function() {
                if (window.removeRecipient) {
                    window.removeRecipient(this);
                }
            });
        });
    });

    // Fonction pour ajouter un destinataire - Si elle n'existe pas déjà dans votre script externe
    if (typeof window.addRecipient === 'undefined') {
        window.addRecipient = function(type, investor) {
            const container = document.getElementById(`${type}-container`);
            const template = document.getElementById('recipient-tag-template');

            if (!container || !template) return;

            // Vérifier si l'investisseur est déjà ajouté
            const existingRecipients = container.querySelectorAll('.recipient-id');
            for (let i = 0; i < existingRecipients.length; i++) {
                if (existingRecipients[i].value === investor.id.toString()) {
                    return; // Déjà ajouté
                }
            }

            // Cloner le template
            const tag = document.importNode(template.content, true).firstElementChild;

            // Remplir les données
            tag.querySelector('.recipient-name').textContent = investor.nom_complet || `${investor.nom} ${investor.prenom}`;
            tag.querySelector('.email-value').textContent = investor.email;

            // Définir l'avatar
            const avatarImg = tag.querySelector('.recipient-avatar');
            if (investor.avatar_url) {
                avatarImg.src = investor.avatar_url;
            } else {
                avatarImg.src = '/images/avatar-placeholder.png';
            }

            // Définir les champs cachés
            const idInput = tag.querySelector('.recipient-id');
            idInput.name = `${type}_ids[]`;
            idInput.value = investor.id;

            const emailInput = tag.querySelector('.recipient-email-input');
            emailInput.name = `${type}_emails[]`;
            emailInput.value = investor.email;

            // Insérer avant l'input
            const input = document.getElementById(`${type}-input`);
            container.insertBefore(tag, input);

            // Effacer l'input et masquer les suggestions
            input.value = '';
            document.getElementById(`${type}-suggestions`).classList.add('hidden');

            // Mettre à jour le compteur
            updateRecipientCount();
        };
    }

    // Fonction pour supprimer un destinataire - Si elle n'existe pas déjà dans votre script externe
    if (typeof window.removeRecipient === 'undefined') {
        window.removeRecipient = function(element) {
            const tag = element.closest('.recipient-tag');
            if (tag) {
                tag.remove();
                updateRecipientCount();
            }
        };
    }

    // Fonction pour mettre à jour le compteur - Si elle n'existe pas déjà dans votre script externe
    if (typeof window.updateRecipientCount === 'undefined') {
        window.updateRecipientCount = function() {
            const toCount = document.querySelectorAll('#to-container .recipient-tag').length;
            const ccCount = document.querySelectorAll('#cc-container .recipient-tag').length;
            const cciCount = document.querySelectorAll('#cci-container .recipient-tag').length;
            const totalCount = toCount + ccCount + cciCount;

            document.getElementById('recipient-count').textContent = totalCount;
        };
    }
</script>
