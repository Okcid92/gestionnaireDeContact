<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Groupes</h2>
            <a href="{{ route('groups.create') }}" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                Nouveau Groupe
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Nom</th>
                                <th class="px-4 py-2 text-left">Description</th>
                                <th class="px-4 py-2 text-left">Contacts</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $group->name }}</td>
                                    <td class="px-4 py-2">{{ $group->description }}</td>
                                    <td class="px-4 py-2">{{ $group->contacts_count }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2">
                                            <a href="{{ route('groups.edit', $group) }}" style="background-color: #16a34a; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">Modifier</a>
                                            <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background-color: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">Aucun groupe trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $groups->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
