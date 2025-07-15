@extends('layouts.app')

<!-- test modification -->


@section('title', 'Planning')

@section('page-header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Planning des Interactions</h1>
            <p class="text-slate-600 mt-1">Vue calendaire de toutes vos interactions</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Investisseur</label>
                <select id="filter-investor" class="select select-bordered w-full">
                    <option value="">Tous les investisseurs</option>
                    @foreach($investors as $investor)
                        <option value="{{ $investor->id }}">{{ $investor->nom_complet }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                <select id="filter-category" class="select select-bordered w-full">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Type d'interaction</label>
                <select id="filter-type" class="select select-bordered w-full">
                    <option value="">Tous les types</option>
                    <option value="Email">📧 Email</option>
                    <option value="Email envoyé">📤 Email envoyé</option>
                    <option value="Réunion">🤝 Réunion</option>
                    <option value="Appel">📞 Appel</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                <select id="filter-statut" class="select select-bordered w-full">
                    <option value="">Tous les statuts</option>
                    <option value="planifié">📅 Planifié</option>
                    <option value="réalisé">✅ Réalisé</option>
                    <option value="annulé">❌ Annulé</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="apply-filters" class="btn btn-primary w-full">Filtrer</button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                <input id="filter-search" type="text" placeholder="Rechercher dans les titres, descriptions, investisseurs..." class="input input-bordered w-full">
            </div>
            
            <div class="flex items-center space-x-4">
                <button id="reset-filters" class="btn btn-outline">Réinitialiser</button>
                <div class="text-sm text-slate-600">
                    <span id="events-count">0</span> événement(s)
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div id="calendar"></div>
    </div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
.fc-event {
    font-weight: 700 !important;
    font-size: 14px !important;
    border-radius: 6px !important;
    padding: 3px 6px !important;
    border: 2px solid rgba(255,255,255,0.2) !important;
}
.fc-event-title {
    font-weight: 700 !important;
    text-shadow: 0 1px 1px rgba(0,0,0,0.2) !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            loadEvents(successCallback, failureCallback);
        },
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventMouseEnter: function(info) {
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed z-50 bg-slate-800 text-white p-3 rounded-lg shadow-lg max-w-sm';
            tooltip.innerHTML = `
                <div class="font-semibold">${info.event.title}</div>
                <div class="text-sm text-slate-300 mt-1">
                    <div>Type: ${info.event.extendedProps.type}</div>
                    <div>Investisseur: ${info.event.extendedProps.investor}</div>
                    <div>Date: ${info.event.start.toLocaleDateString('fr-FR')}</div>
                    ${info.event.extendedProps.description !== 'Aucune description' ? '<div>Description: ' + info.event.extendedProps.description + '</div>' : ''}
                </div>
            `;
            tooltip.id = 'event-tooltip';
            document.body.appendChild(tooltip);
            
            const rect = info.el.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.top - 80) + 'px';
        },
        eventMouseLeave: function(info) {
            const tooltip = document.getElementById('event-tooltip');
            if (tooltip) tooltip.remove();
        }
    });
    
    calendar.render();

    function loadEvents(successCallback, failureCallback) {
        const params = new URLSearchParams({
            investor_id: document.getElementById('filter-investor').value,
            category_id: document.getElementById('filter-category').value,
            type: document.getElementById('filter-type').value,
            statut: document.getElementById('filter-statut').value,
            search: document.getElementById('filter-search').value
        });

        fetch(`{{ route('planning.events') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('events-count').textContent = data.length;
                successCallback(data);
            })
            .catch(error => failureCallback(error));
    }

    document.getElementById('apply-filters').addEventListener('click', function() {
        calendar.refetchEvents();
    });
    
    document.getElementById('reset-filters').addEventListener('click', function() {
        document.getElementById('filter-investor').value = '';
        document.getElementById('filter-category').value = '';
        document.getElementById('filter-type').value = '';
        document.getElementById('filter-statut').value = '';
        document.getElementById('filter-search').value = '';
        calendar.refetchEvents();
    });
    
    document.getElementById('filter-search').addEventListener('input', function() {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            calendar.refetchEvents();
        }, 500);
    });

    function showEventDetails(event) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4">${event.title}</h3>
                <div class="space-y-2">
                    <p><strong>Type:</strong> ${event.extendedProps.type}</p>
                    <p><strong>Investisseur:</strong> ${event.extendedProps.investor}</p>
                    <p><strong>Date:</strong> ${event.start.toLocaleDateString('fr-FR')}</p>
                    ${event.extendedProps.description ? `<p><strong>Description:</strong> ${event.extendedProps.description}</p>` : ''}
                </div>
                <button onclick="this.closest('.fixed').remove()" class="mt-4 btn btn-primary">Fermer</button>
            </div>
        `;
        document.body.appendChild(modal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.remove();
        });
    }
});
</script>
@endpush