<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau Groupe</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Nom</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; border: none; cursor: pointer;">Créer</button>
                            <a href="{{ route('groups.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
