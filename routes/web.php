<?php

use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\CategorieInvestisseurController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvestorEmailController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
        return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les organisations - sans middleware global can:view
    Route::resource('organisations', OrganisationController::class);

    // Routes additionnelles pour les organisations
    Route::get('/organisations/{organisation}/logo', [OrganisationController::class, 'downloadLogo'])
        ->name('organisations.logo')
        ->middleware('can:view,organisation');

    Route::post('/organisations/{organisation}/toggle-status', [OrganisationController::class, 'toggleStatus'])
        ->name('organisations.toggle-status')
        ->middleware('can:update,organisation');

    Route::get('categories-investisseurs', [CategorieInvestisseurController::class, 'index'])
        ->name('categories-investisseurs.index')
        ->middleware('can:viewAny,App\Models\CategorieInvestisseur');

// Create route - requires create permission
    Route::get('categories-investisseurs/create', [CategorieInvestisseurController::class, 'create'])
        ->name('categories-investisseurs.create')
        ->middleware('can:create,App\Models\CategorieInvestisseur');

// Store route - requires create permission
    Route::post('categories-investisseurs', [CategorieInvestisseurController::class, 'store'])
        ->name('categories-investisseurs.store')
        ->middleware('can:create,App\Models\CategorieInvestisseur');

// Show route - requires view permission with model instance
    Route::get('categories-investisseurs/{categorieInvestisseur}', [CategorieInvestisseurController::class, 'show'])
        ->name('categories-investisseurs.show')
        ->middleware('can:view,categorieInvestisseur');

// Edit route - requires update permission with model instance
    Route::get('categories-investisseurs/{categorieInvestisseur}/edit', [CategorieInvestisseurController::class, 'edit'])
        ->name('categories-investisseurs.edit')
        ->middleware('can:update,App\Models\CategorieInvestisseur');

// Update route - requires update permission with model instance
    Route::put('categories-investisseurs/{categorieInvestisseur}', [CategorieInvestisseurController::class, 'update'])
        ->name('categories-investisseurs.update')
        ->middleware('can:update,App\Models\CategorieInvestisseur');

// Delete route - requires delete permission with model instance
    Route::delete('categories-investisseurs/{categorieInvestisseur}', [CategorieInvestisseurController::class, 'destroy'])
        ->name('categories-investisseurs.destroy')
        ->middleware('can:delete,categorieInvestisseur');

    Route::post('/categories-investisseurs/reorder', [CategorieInvestisseurController::class, 'reorder'])
        ->name('categories-investisseurs.reorder')
        ->middleware('can:reorder,App\Models\CategorieInvestisseur');

    Route::get('/investisseurs', [InvestorController::class, 'index'])
        ->name('investors.index')
        ->middleware('can:viewAny,App\Models\Investor');


    Route::get('/investisseurs/create', [InvestorController::class, 'create'])
        ->name('investors.create')
        ->middleware('can:create,App\Models\Investor');
    Route::post('/investisseurs', [InvestorController::class, 'store'])
        ->name('investors.store')
        ->middleware('can:create,App\Models\Investor');

    Route::get('/investisseurs/{investor}', [InvestorController::class, 'show'])
        ->name('investors.show')
        ->middleware('can:view,investor');
    Route::get('/investisseurs/{investor}/edit', [InvestorController::class, 'edit'])
        ->name('investors.edit')
        ->middleware('can:update,investor');

    Route::patch('/investisseurs/{investor}', [InvestorController::class, 'update'])
        ->name('investors.update')
        ->middleware('can:update,investor');

    Route::delete('/investisseurs/{investor}', [InvestorController::class, 'destroy'])
        ->name('investors.destroy')
        ->middleware('can:delete,investor');

    Route::get('/investisseurs/{investor}/timeline', [InvestorController::class, 'timeline'])
        ->name('investors.timeline')
        ->middleware('can:view,investor');

    Route::get('/investisseurs/{investor}/export', [InvestorController::class, 'export'])
        ->name('investors.export')
        ->middleware('can:view,investor');

    Route::get('/investisseurs/{investor}/export-pdf', [InvestorController::class, 'exportPdf'])
        ->name('investors.export-pdf')
        ->middleware('can:view,investor');



    // Routes AJAX pour les commentaires d'investisseurs
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::post('/investors/{investor}/comments', [App\Http\Controllers\Ajax\InvestorCommentController::class, 'store'])
            ->name('investor.comments.store')
            ->middleware('can:view,investor');

        Route::get('/investors/{investor}/comments', [App\Http\Controllers\Ajax\InvestorCommentController::class, 'index'])
            ->name('investor.comments.index')
            ->middleware('can:view,investor');

        Route::patch('/comments/{comment}', [App\Http\Controllers\Ajax\InvestorCommentController::class, 'update'])
            ->name('comments.update');

        Route::delete('/comments/{comment}', [App\Http\Controllers\Ajax\InvestorCommentController::class, 'destroy'])
            ->name('comments.destroy');

        // Routes pour les interactions
        Route::post('/investors/{investor}/interactions', [App\Http\Controllers\Ajax\InteractionController::class, 'store'])
            ->name('investor.interactions.store')
            ->middleware('can:view,investor');

        Route::get('/investors/{investor}/interactions', [App\Http\Controllers\Ajax\InteractionController::class, 'index'])
            ->name('investor.interactions.index')
            ->middleware('can:view,investor');
    });

    // Routes AJAX pour les activités
    Route::prefix('ajax')->name('ajax.')->middleware('auth')->group(function () {
        // Routes pour les activités des utilisateurs
        Route::get('/users/{user}/activities', [App\Http\Controllers\Ajax\ActivityController::class, 'getUserActivities'])
            ->name('user.activities')
            ->middleware('can:view,user');

        // Route pour les détails d'une activité
        Route::get('/activities/{activity}', [App\Http\Controllers\Ajax\ActivityController::class, 'getActivityDetails'])
            ->name('activity.details')
            ->middleware('can:viewAny,App\Models\User');
    });

    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])
        ->middleware('can:viewAny,App\Models\Contact')
        ->name('contacts.index');

    Route::get('/contacts/create', [App\Http\Controllers\ContactController::class, 'create'])
        ->middleware('can:create,App\Models\Contact')
        ->name('contacts.create');

    Route::post('/contacts', [App\Http\Controllers\ContactController::class, 'store'])
        ->middleware('can:create,App\Models\Contact')
        ->name('contacts.store');

    Route::get('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'show'])
        ->middleware('can:view,contact')
        ->name('contacts.show');

    Route::get('/contacts/{contact}/edit', [App\Http\Controllers\ContactController::class, 'edit'])
        ->middleware('can:update,contact')
        ->name('contacts.edit');

    Route::put('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'update'])
        ->middleware('can:update,contact')
        ->name('contacts.update');

    Route::delete('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'destroy'])
        ->middleware('can:delete,contact')
        ->name('contacts.destroy');

    //toggle status contact
    Route::post('/contacts/{contact}/toggle-status', [App\Http\Controllers\ContactController::class, 'toggleStatus'])
        ->name('contacts.toggle-status')
        ->middleware('can:update,contact');

    Route::post('/contacts/{contact}/organisations', [App\Http\Controllers\ContactController::class, 'attach'])
        ->middleware('can:manageRelationships,contact')
        ->name('contacts.attach-organisation');

    // Détacher une organisation d'un contact
    Route::delete('/contacts/{contact}/organisations/{organisation}', [App\Http\Controllers\ContactController::class, 'detach'])
        ->middleware('can:manageRelationships,contact')
        ->name('contacts.detach-organisation');


    // Routes pour les emails
    Route::prefix('emails')->name('emails.')->group(function () {

        // Liste des emails avec filtres
        Route::get('/', [App\Http\Controllers\EmailController::class, 'index'])
            ->name('index')
            ->middleware('can:view emails');

        // API pour l'autocomplete des investisseurs
        Route::get('/search-investors', [App\Http\Controllers\EmailController::class, 'searchInvestors'])
            ->name('search-investors')
            ->middleware('can:view emails');


        // Afficher un email spécifique
        Route::get('/{email}', [App\Http\Controllers\EmailController::class, 'show'])
            ->name('show')
            ->middleware('can:view emails');

        // Composer et envoyer un email
        Route::get('/compose', [App\Http\Controllers\EmailController::class, 'compose'])
            ->name('compose')
            ->middleware('can:send emails');

        Route::post('/send', [App\Http\Controllers\EmailController::class, 'send'])
            ->name('send')
            ->middleware('can:send bulk emails');


        // Routes additionnelles pour les fonctionnalités avancées

        // Télécharger les pièces jointes d'emails
        Route::get('/{email}/download-attachment', [App\Http\Controllers\EmailController::class, 'downloadAttachment'])
            ->name('download-attachment')
            ->middleware('can:view,email');

        // Exporter les emails
        Route::get('/export', [App\Http\Controllers\EmailController::class, 'export'])
            ->name('export')
            ->middleware('can:export emails');

        // Statistiques des emails
        Route::get('/stats', [App\Http\Controllers\EmailController::class, 'stats'])
            ->name('stats')
            ->middleware('can:view email stats');

        // Gestion des templates d'emails
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [App\Http\Controllers\EmailTemplateController::class, 'index'])
                ->name('index')
                ->middleware('can:manage email templates');

            Route::post('/', [App\Http\Controllers\EmailTemplateController::class, 'store'])
                ->name('store')
                ->middleware('can:manage email templates');

            Route::put('/{template}', [App\Http\Controllers\EmailTemplateController::class, 'update'])
                ->name('update')
                ->middleware('can:manage email templates');

            Route::delete('/{template}', [App\Http\Controllers\EmailTemplateController::class, 'destroy'])
                ->name('destroy')
                ->middleware('can:manage email templates');
        });
    });

    // Routes pour les interactions
    Route::get('/interactions', [InteractionController::class, 'index'])->name('interactions.index');
    Route::get('/interactions/{interaction}', [InteractionController::class, 'show'])->name('interactions.show');
    Route::get('/interactions/{interaction}/download', [InteractionController::class, 'downloadAttachment'])->name('interactions.download');

    // Routes pour le journal d'activités
    Route::middleware(['auth'])->group(function () {
        Route::get('/activities', [ActivityLogController::class, 'index'])->name('activity.index');
        Route::get('/activities/{activity}', [ActivityLogController::class, 'show'])->name('activity.show');
    });

    // Routes pour le planning
    Route::get('/planning', [App\Http\Controllers\PlanningController::class, 'index'])->name('planning.index');
    Route::get('/planning/events', [App\Http\Controllers\PlanningController::class, 'getEvents'])->name('planning.events');
});

Route::get('/investors/interactions/{interaction}/download', [InvestorController::class, 'downloadAttachment'])
    ->name('investors.download-attachment')
    ->middleware('auth');

// Route pour envoyer un email à un investisseur
Route::post('/investors/{investor}/send-email', [InvestorController::class, 'sendEmail'])
    ->name('investors.send-email')
    ->middleware(['auth', 'can:view,investor']);

// Routes pour la gestion des utilisateurs avec middlewares d'autorisation
Route::middleware(['auth'])->group(function () {
    // Liste des utilisateurs
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('can:viewAny,App\Models\User');

    // Créer un utilisateur
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:create,App\Models\User');
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:create,App\Models\User');

    // Afficher un utilisateur
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:view,user');

    // Modifier un utilisateur
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:update,user');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:update,user');
    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:update,user');

    // Supprimer un utilisateur
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:delete,user');

    // Activer/désactiver un utilisateur
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status')
        ->middleware('can:toggleStatus,user');
});

Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les activités
    Route::get('api/users/{user}/activities', [ActivityController::class, 'getUserActivities']);
    Route::get('api/activities/{activity}', [ActivityController::class, 'getActivityDetails']);
    Route::get('api/activities', [ActivityController::class, 'getAllActivities']);
});

require __DIR__.'/auth.php';
