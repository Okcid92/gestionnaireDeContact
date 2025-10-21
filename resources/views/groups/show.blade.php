<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Groupe: {{ $group->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <strong>Description:</strong> {{ $group->description ?? 'Aucune description' }}
                    </div>
                    
                    <h3 class="text-lg font-semibold mb-4">Contacts dans ce groupe</h3>
                    
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Nom</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $contact->full_name }}</td>
                                    <td class="px-4 py-2">{{ $contact->email }}</td>
                                    <td class="px-4 py-2">{{ $contact->phone }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-gray-500">Aucun contact dans ce groupe</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">{{ $contacts->links() }}</div>
                    
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('groups.edit', $group) }}" style="background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Modifier</a>
                        <a href="{{ route('groups.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>