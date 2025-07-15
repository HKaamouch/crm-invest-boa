<!-- Modal nouvelle interaction -->
<dialog id="interaction-modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg">Nouvelle interaction avec {{ $investor->nom_complet }}</h3>
            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="closeInteractionModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="interaction-form" enctype="multipart/form-data">
            @csrf

            <!-- Type d'interaction -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Type d'interaction <span class="text-red-500">*</span></span>
                </label>
                <select name="type" id="interaction-type" class="select select-bordered w-full" required>
                    <option value="">Sélectionner un type</option>
                    @foreach($interactionTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Date <span class="text-red-500">*</span></span>
                </label>
                <input type="date"
                       name="date_interaction"
                       id="interaction-date"
                       class="input input-bordered w-full"
                       value="{{ $getTodayDate() }}"
                       required>
            </div>

            <!-- Description -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Description <span class="text-red-500">*</span></span>
                </label>
                <textarea name="description"
                          id="interaction-description"
                          rows="6"
                          class="textarea textarea-bordered w-full"
                          placeholder="Décrivez l'interaction en détail..."
                          required></textarea>
                <div class="label">
                    <span class="label-text-alt text-slate-500">Maximum 2000 caractères</span>
                </div>
            </div>

            <!-- Pièce jointe -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-semibold">Pièce jointe</span>
                </label>
                <input type="file"
                       name="piece_jointe"
                       id="interaction-attachment"
                       class="file-input file-input-bordered w-full"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div class="label">
                    <span class="label-text-alt text-slate-500">PDF, Word, Images - Max 10 Mo</span>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="closeInteractionModal()">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary" id="save-interaction-btn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openInteractionModal() {
        document.getElementById('interaction-modal').showModal();
    }

    function closeInteractionModal() {
        document.getElementById('interaction-modal').close();
        document.getElementById('interaction-form').reset();
        // Remettre la date à aujourd'hui
        document.getElementById('interaction-date').value = '{{ $getTodayDate() }}';
    }

    // Gestionnaire de soumission du formulaire
    document.getElementById('interaction-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btn = document.getElementById('save-interaction-btn');
        const originalText = btn.innerHTML;

        // Désactiver le bouton et montrer le loading
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner loading-sm mr-2"></span>Enregistrement...';

        try {
            const formData = new FormData(this);

            const response = await fetch(`{{ route('ajax.investor.interactions.store', $investor) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                // Fermer la modal
                closeInteractionModal();

                // Afficher le message de succès
                showToast('Interaction ajoutée avec succès', 'success');

                // Ajouter la nouvelle interaction à la liste
                addInteractionToList(result.interaction);

                // Recharger les interactions si nécessaire
                if (window.refreshInteractions) {
                    window.refreshInteractions();
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

    // Fonction pour ajouter une interaction à la liste
    function addInteractionToList(interaction) {
        const container = document.getElementById('interactions-container');
        if (container) {
            // Créer l'élément HTML de la nouvelle interaction
            const interactionHtml = createInteractionCard(interaction);
            container.insertAdjacentHTML('afterbegin', interactionHtml);

            // Si c'était vide, supprimer le message "aucune interaction"
            const emptyMessage = container.querySelector('.text-center.py-8');
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }

    // Fonction pour créer le HTML d'une carte d'interaction
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

        const colorClass = typeColors[interaction.type] || 'bg-slate-50 text-slate-600';
        const badgeClass = typeBadges[interaction.type] || 'badge-outline';

        return `
        <div class="flex items-start space-x-4 p-4 hover:bg-slate-50 rounded-lg transition-colors">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- Icon will be determined by type -->
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="badge ${badgeClass} badge-sm">${interaction.type}</span>
                        <span class="text-sm text-slate-500">${interaction.date_interaction_formatee}</span>
                    </div>
                    <time class="text-xs text-slate-400">Maintenant</time>
                </div>
                <div class="text-sm text-slate-900 mb-2">
                    ${interaction.description_courte}
                </div>
                <div class="flex items-center text-xs text-slate-500">
                    <img class="w-5 h-5 rounded-full mr-2"
                         src="https://ui-avatars.com/api/?name=${encodeURIComponent(interaction.user_name)}&size=20&background=e0e7ff&color=2563eb"
                         alt="${interaction.user_name}">
                    <span>${interaction.user_name}</span>
                </div>
            </div>
        </div>
    `;
    }
</script>
