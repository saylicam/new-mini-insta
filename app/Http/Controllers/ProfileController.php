<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire d'édition du profil.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Met à jour le profil utilisateur, y compris la photo de profil.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:2048', // Validation pour l'image
        ]);

        $user = auth()->user();

        // Gestion de la photo de profil
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');

            // Supprime l'ancienne image si elle existe
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $imagePath;
        }

        // Mise à jour des autres champs
        $user->name = $request->input('name');
        $user->first_name = $request->input('first_name');
        $user->bio = $request->input('bio');
        $user->save();

        return redirect()->route('profile.my')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Affiche le profil public d'un utilisateur.
     */
    public function show(User $user)
    {
        $posts = $user->posts()->with('likes')->latest()->get();
        $followers = $user->followers;
        $following = $user->following;

        return view('profile.show', compact('user', 'posts', 'followers', 'following'));
    }

    /**
     * Affiche le profil de l'utilisateur connecté.
     */
    public function myProfile()
    {
        $user = auth()->user();

        if (!$user) {
            abort(404, 'Utilisateur non trouvé.');
        }

        $posts = $user->posts()->with('likes')->latest()->get();
        $followers = $user->followers;
        $following = $user->following;

        return view('profile.show', compact('user', 'posts', 'followers', 'following'));
    }
    public function search(Request $request)
{
    // Récupère le terme de recherche depuis la requête
    $searchTerm = $request->input('query');

    // Cherche les utilisateurs dont le nom contient le terme
    $users = User::where('name', 'like', "%{$searchTerm}%")->get();

    // Retourne une vue avec les résultats de recherche
    return view('profile.search', compact('users', 'searchTerm'));
}
public function follow(User $user)
{
    $currentUser = auth()->user();

    // Vérifie si l'utilisateur suit déjà cet utilisateur
    if (!$currentUser->following()->where('followed_id', $user->id)->exists()) {
        $currentUser->following()->create(['followed_id' => $user->id]);
    }

    return redirect()->route('profile.show', $user)->with('success', 'Vous suivez désormais cet utilisateur.');
}
public function unfollow(User $user)
{
    $currentUser = auth()->user();

    // Supprime la relation de suivi
    $currentUser->following()->where('followed_id', $user->id)->delete();

    return redirect()->route('profile.show', $user)->with('success', 'Vous ne suivez plus cet utilisateur.');
}

}
