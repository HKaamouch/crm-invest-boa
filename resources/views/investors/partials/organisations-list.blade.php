<!-- Organisations liées -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <h2 class="text-lg font-semibold text-slate-900">
            Organisations ({{ $investor->organisations->count() }})
        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($investor->organisations as $organisation)
                <x-investor.organisation-card
                    :organisation="$organisation"
                    :pivot="$organisation->pivot"
                />
            @endforeach
        </div>
    </div>
</div>
