<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }} - {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Informations de l'utilisateur -->
            <div class="bg-white shadow sm:rounded-lg p-4">
                <h3 class="font-semibold">Nom : {{ $user->name }}</h3>
                <p>Email : {{ $user->email }}</p>
                <p>Bio : {{ $user->bio ?? 'Pas de bio' }}</p>
                <p>Membre depuis : {{ $user->created_at->format('d/m/Y') }}</p>

                @if ($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Photo de profil" class="mt-4 rounded-lg w-32 h-32">
                @else
                    <p class="text-gray-500">Aucune photo de profil</p>
                @endif

                <!-- Boutons Follow/Unfollow -->
                @if (auth()->id() !== $user->id)
                    @if (auth()->user()->following()->where('followed_id', $user->id)->exists())
                        <form method="POST" action="{{ route('profile.unfollow', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                Se désabonner
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('profile.follow', $user) }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Suivre
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Publications -->
            <div class="bg-white shadow sm:rounded-lg p-4">
                <h3 class="font-semibold">Publications</h3>
                @forelse ($posts as $post)
                    <div class="p-2 border-b">
                        <p>{{ $post->content }}</p>
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="mt-2">
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Aucune publication disponible.</p>
                @endforelse
            </div>

            <!-- Abonnés et abonnements -->
            <div class="bg-white shadow sm:rounded-lg p-4">
                <h3 class="font-semibold">Abonnés ({{ $followers->count() }})</h3>
                <ul>
                    @forelse ($followers as $follower)
                        <li>{{ $follower->follower->name }}</li>
                    @empty
                        <p class="text-gray-500">Aucun abonné.</p>
                    @endforelse
                </ul>

                <h3 class="font-semibold mt-4">Abonnements ({{ $following->count() }})</h3>
                <ul>
                    @forelse ($following as $followed)
                        <li>{{ $followed->followed->name }}</li>
                    @empty
                        <p class="text-gray-500">Aucun abonnement.</p>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
