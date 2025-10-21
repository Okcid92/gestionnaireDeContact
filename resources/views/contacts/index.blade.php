<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Contacts') }}
    </h2>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div style="background-color: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px; border-radius: 4px; margin-bottom: 16px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Boutons d'action -->
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Gestion des Contacts</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('groups.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Groupes
                            </a>
                            <a href="{{ route('contacts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Nouveau Contact
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <form method="GET" class="mb-6 flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="border rounded px-3 py-2">
                        <select name="group" class="border rounded px-3 py-2">
                            <option value="">Tous les groupes</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; border: none; cursor: pointer;">Filtrer</button>
<a href="{{ route('contacts.index') }}" style="background-color: #9ca3af; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Reset</a>
                    </form>

                    <!-- Liste des contacts -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Nom</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Téléphone</th>
                                    <th class="px-4 py-2 text-left">Groupes</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $contact->full_name }}</td>
                                        <td class="px-4 py-2">{{ $contact->email }}</td>
                                        <td class="px-4 py-2">{{ $contact->phone }}</td>
                                        <td class="px-4 py-2">
                                            @foreach($contact->groups as $group)
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">{{ $group->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex gap-2">
                                                <a href="{{ route('contacts.show', $contact) }}" style="background-color: #3b82f6; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">Voir</a>
                                                <a href="{{ route('contacts.edit', $contact) }}" style="background-color: #16a34a; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">Modifier</a>
                                                <form method="POST" action="{{ route('contacts.destroy', $contact) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background-color: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Aucun contact trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $contacts->links() }}
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="mt-6 flex gap-2 justify-end">
                        <a href="{{ route('groups.index') }}" style="background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                            Groupes
                        </a>
                        <a href="{{ route('contacts.create') }}" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                            Nouveau Contact
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
