<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">Nom : {{ $user->name }}</h3>
                <p class="text-gray-600">Email : {{ $user->email }}</p>
                <p class="text-gray-600">Membre depuis : {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>

