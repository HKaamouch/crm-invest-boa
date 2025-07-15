<footer class="bg-white border-t border-slate-200 px-4 sm:px-6 lg:px-8 py-6 mt-auto">
    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <div class="flex items-center space-x-4">
            <div class="text-sm text-slate-600">
                © {{ date('Y') }} <span class="font-semibold">Bank of Africa</span>. Tous droits réservés.
            </div>
            <div class="hidden md:block text-xs text-slate-400">
                Version 1.0.0
            </div>
        </div>

        <div class="flex items-center space-x-6 text-sm text-slate-500">
            <a href="#" class="hover:text-slate-700 transition-colors duration-200">
                Support technique
            </a>
            <a href="#" class="hover:text-slate-700 transition-colors duration-200">
                Documentation
            </a>
            <a href="#" class="hover:text-slate-700 transition-colors duration-200">
                Confidentialité
            </a>
        </div>
    </div>

    <!-- Status bar -->
    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
        <div class="flex items-center space-x-4 text-xs text-slate-500">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                <span>Système opérationnel</span>
            </div>
            <div>
                Dernière sauvegarde: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <span>Connexion sécurisée</span>
        </div>
    </div>
</footer>
