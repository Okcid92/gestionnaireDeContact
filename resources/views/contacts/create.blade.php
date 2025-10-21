<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau Contact</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 4px; margin-bottom: 16px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('contacts.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Prénom</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Nom</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Adresse</label>
                            <textarea name="address" class="w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Groupes</label>
                            @foreach($groups as $group)
                                <label class="inline-flex items-center mr-4">
                                    <input type="checkbox" name="groups[]" value="{{ $group->id }}" class="mr-2">
                                    {{ $group->name }}
                                </label>
                            @endforeach
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; border: none; cursor: pointer;">Créer</button>
                            <a href="{{ route('contacts.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
