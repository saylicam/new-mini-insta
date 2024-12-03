<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Champ Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>

                    <!-- Champ Prénom -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input
                            id="first_name"
                            name="first_name"
                            type="text"
                            value="{{ old('first_name', $user->first_name) }}"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <!-- Champ Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                        <textarea
                            id="bio"
                            name="bio"
                            rows="4"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <!-- Champ Photo de profil -->
                    <div>
                        <label for="profile_image" class="block text-sm font-medium text-gray-700">Photo de profil</label>
                        <input
                            id="profile_image"
                            name="profile_image"
                            type="file"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none"
                        >
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
