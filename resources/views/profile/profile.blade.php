<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }} - {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4">
                <h3 class="font-semibold">Nom : {{ $user->name }}</h3>
                <p class="text-gray-600">Prénom : {{ $user->first_name ?? 'Pas de prénom' }}</p>
                <p class="text-gray-600">Email : {{ $user->email }}</p>
                <p class="text-gray-600">Bio : {{ $user->bio ?? 'Pas de bio' }}</p>
                <p class="text-gray-600">Membre depuis : {{ $user->created_at->format('d/m/Y') }}</p>

                @if ($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Photo de profil" class="mt-4 rounded-lg w-32 h-32">
                @else
                    <p class="text-gray-500">Aucune photo de profil</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
