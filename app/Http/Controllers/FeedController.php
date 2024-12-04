<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class FeedController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();

        // Récupérer les IDs des utilisateurs suivis par l'utilisateur connecté
        $followedUserIds = $currentUser->following->pluck('followed_id')->toArray();

        // Inclure également les posts de l'utilisateur lui-même
        $followedUserIds[] = $currentUser->id;

        // Charger les posts des utilisateurs suivis, triés par nombre de likes et date
        $posts = Post::with('user', 'likes', 'comments.user')
            ->withCount('likes') // Compter les likes pour chaque post
            ->whereIn('user_id', $followedUserIds) // Récupérer uniquement les posts des utilisateurs suivis
            ->orderBy('likes_count', 'desc') // Trier par nombre de likes décroissant
            ->latest('created_at') // Trier par date en cas d'égalité
            ->get();

        return view('feed', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validation pour une image
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('feed')->with('success', 'Post publié avec succès !');
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès !');
    }

    public function like(Post $post)
    {
        // Vérifie si l'utilisateur a déjà liké le post
        $existingLike = $post->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            // Si oui, supprime le like
            $existingLike->delete();
            return back()->with('success', 'Like retiré.');
        } else {
            // Sinon, ajoute un like
            $post->likes()->create([
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', 'Post liké.');
        }
    }
}
