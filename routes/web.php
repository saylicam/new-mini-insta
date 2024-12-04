<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

// Route d'accueil - Redirige vers la page d'inscription ou le fil d'actualité
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('feed'); // Redirige les utilisateurs connectés vers le feed
    }
    return redirect()->route('register'); // Redirige les utilisateurs non connectés vers l'inscription
});

// Tableau de bord - Redirige directement vers le fil d'actualité ou l'inscription
Route::get('/dashboard', function () {
    if (auth()->check()) {
        return redirect()->route('feed'); // Redirige les utilisateurs connectés vers le feed
    }
    return redirect()->route('register'); // Redirige les utilisateurs non connectés vers l'inscription
})->name('dashboard');

// Routes nécessitant une authentification
Route::middleware('auth')->group(function () {

    // Route pour le fil d'actualité
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::post('/feed', function (Request $request) {
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // Validation pour les images
        ]);

        // Vérifie si un fichier est fourni avant de le stocker
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('images', 'public')
            : null;

        // Création du post
        \App\Models\Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('feed')->with('success', 'Post publié avec succès !');
    })->name('feed.store');

    // Routes pour les interactions avec les posts
    Route::post('/posts/{post}/like', [FeedController::class, 'like'])->name('post.like');
    Route::post('/posts/{post}/comment', [FeedController::class, 'comment'])->name('post.comment');

    // Routes pour les profils utilisateurs
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // Modifier son propre profil
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update'); // Mettre à jour son propre profil
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show'); // Voir un profil public

    // Route alternative pour contourner les problèmes d'accès à "edit profile"
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('edit.profile');

    // Route pour afficher le profil de l'utilisateur connecté
    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('profile.my');

    // Routes pour suivre et ne plus suivre un utilisateur
    Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::delete('/profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');

    // Route pour la recherche d'utilisateurs
    Route::get('/search', [ProfileController::class, 'search'])->name('search');

    // Route de test pour déboguer
    Route::get('/profile/edit-debug', function () {
        return response()->json(['message' => 'Debug: route fonctionne bien.']);
    })->name('profile.edit.debug');
});

// Routes d'authentification générées par Breeze
require __DIR__ . '/auth.php';
