<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fil d\'actualité') }}
        </h2>
    </x-slot>

    <!-- Formulaire de création de post -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4">
                <form method="POST" action="{{ route('feed.store') }}" enctype="multipart/form-data">
                    @csrf
                    <textarea
                        name="content"
                        placeholder="Quoi de neuf ?"
                        class="w-full border p-2 rounded"
                        rows="3"
                        required></textarea>
                    <input type="file" name="image" class="mt-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
                        Publier
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Affichage des posts -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach ($posts as $post)
                <div class="bg-white shadow sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">{{ $post->user->name }}</h3>
                        <span class="text-sm text-gray-500">
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-gray-600 mt-2">{{ $post->content }}</p>

                    <!-- Affichage de l'image si elle existe -->
                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="mt-4 rounded-lg">
                    @endif

                    <div class="mt-4 flex items-center space-x-4">
                        <!-- Compteur de likes -->
                        <span>{{ $post->likes->count() }} like{{ $post->likes->count() > 1 ? 's' : '' }}</span>

                        <!-- Bouton like/unlike -->
                        <form method="POST" action="{{ route('post.like', $post->id) }}">
                            @csrf
                            <button type="submit" class="text-blue-500 hover:underline">
                                {{ $post->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                            </button>
                        </form>
                    </div>

                    <!-- Affichage des commentaires -->
                    <div class="mt-6">
                        <h4 class="font-semibold">Commentaires</h4>
                        <div class="space-y-4 mt-2">
                            @foreach ($post->comments as $comment)
                                <div class="bg-gray-100 p-2 rounded">
                                    <p class="text-sm text-gray-700"><strong>{{ $comment->user->name }}</strong> : {{ $comment->content }}</p>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Formulaire pour ajouter un commentaire -->
                        <form method="POST" action="{{ route('post.comment', $post->id) }}" class="mt-4">
                            @csrf
                            <textarea
                                name="content"
                                placeholder="Ajouter un commentaire..."
                                class="w-full border p-2 rounded"
                                rows="2"
                                required></textarea>
                            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded mt-2">
                                Commenter
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
