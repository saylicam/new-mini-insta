<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Résultats de recherche') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($users->isEmpty())
                <p class="text-gray-600">Aucun utilisateur trouvé pour "{{ $searchTerm }}"</p>
            @else
                <ul class="bg-white shadow sm:rounded-lg p-4">
                    @foreach ($users as $user)
                        <li class="py-2">
                            <a href="{{ route('profile.show', $user->id) }}" class="text-blue-500 hover:underline">
                                {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>

