<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Détails du Contact</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <strong>Nom complet:</strong> {{ $contact->full_name }}
                    </div>
                    <div class="mb-4">
                        <strong>Email:</strong> {{ $contact->email }}
                    </div>
                    <div class="mb-4">
                        <strong>Téléphone:</strong> {{ $contact->phone ?? 'Non renseigné' }}
                    </div>
                    <div class="mb-4">
                        <strong>Adresse:</strong> {{ $contact->address ?? 'Non renseignée' }}
                    </div>
                    <div class="mb-4">
                        <strong>Groupes:</strong>
                        @forelse($contact->groups as $group)
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">{{ $group->name }}</span>
                        @empty
                            Aucun groupe
                        @endforelse
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('contacts.edit', $contact) }}" style="background-color: #16a34a; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Modifier</a>
                        <a href="{{ route('contacts.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>