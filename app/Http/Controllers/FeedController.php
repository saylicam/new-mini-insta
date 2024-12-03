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
        // Charger les posts avec les relations user, likes et comments
        $posts = Post::with('user', 'likes', 'comments.user')->latest()->get();
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
