<!-- Modal d'envoi d'email -->
<dialog id="email-modal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg">Envoyer un email à {{ $investor->nom_complet }}</h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="closeEmailModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="email-form" enctype="multipart/form-data">
            @csrf

            <!-- Destinataire -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Destinataire</span>
                </label>
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                    <img src="{{ $investor->avatar_url }}"
                         alt="{{ $investor->nom_complet }}"
                         class="w-8 h-8 rounded-full">
                    <div>
                        <p class="font-medium text-slate-900">{{ $investor->nom_complet }}</p>
                        <p class="text-sm text-slate-600">{{ $investor->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Objet -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Objet <span class="text-red-500">*</span></span>
                </label>
                <input type="text"
                       name="objet"
                       id="email-objet"
                       class="input input-bordered w-full"
                       placeholder="{{ $getSuggestedSubject() }}"
                       required>
                <div class="label">
                    <span class="label-text-alt text-slate-500">L'objet de votre message</span>
                </div>
            </div>

            <!-- Message -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Message <span class="text-red-500">*</span></span>
                </label>
                <textarea name="message"
                          id="email-message"
                          rows="8"
                          class="textarea textarea-bordered w-full"
                          placeholder="Rédigez votre message..."
                          required></textarea>
                <div class="label">
                    <span class="label-text-alt text-slate-500">Votre message sera envoyé depuis votre adresse email professionnelle</span>
                </div>
            </div>

            <!-- Pièces jointes -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-semibold">Pièces jointes</span>
                </label>
                <input type="file"
                       name="pieces_jointes[]"
                       id="email-attachments"
                       class="file-input file-input-bordered w-full"
                       multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div class="label">
                    <span class="label-text-alt text-slate-500">PDF, Word, Images - Max 10 Mo par fichier</span>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="closeEmailModal()">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary" id="send-email-btn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openEmailModal() {
        document.getElementById('email-modal').showModal();
    }

    function closeEmailModal() {
        document.getElementById('email-modal').close();
        document.getElementById('email-form').reset();
    }

    // Gestionnaire de soumission du formulaire
    document.getElementById('email-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btn = document.getElementById('send-email-btn');
        const originalText = btn.innerHTML;

        // Désactiver le bouton et montrer le loading
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner loading-sm mr-2"></span>Envoi...';

        try {
            const formData = new FormData(this);

            const response = await fetch(`{{ route('investors.send-email', $investor) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                // Fermer la modal
                closeEmailModal();

                // Afficher le message de succès
                showToast('Email envoyé avec succès', 'success');

                // Recharger les interactions si nécessaire
                if (window.refreshInteractions) {
                    window.refreshInteractions();
                }
            } else {
                showToast(result.message || 'Erreur lors de l\'envoi', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de l\'envoi', 'error');
        } finally {
            // Réactiver le bouton
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });

    // Fonction pour afficher les toasts
    function showToast(message, type = 'info') {
        // Créer l'élément toast
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} fixed top-4 right-4 w-auto max-w-sm z-50 animate-fade-in-scale`;
        toast.innerHTML = `
        <svg class="w-6 h-6 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
            ${type === 'success' ?
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'}
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
    }
</script>
