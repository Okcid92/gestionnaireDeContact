<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContactController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $query = auth()->user()->contacts()->with('groups');
        
        if ($request->filled('group')) {
            $query->whereHas('groups', function ($q) use ($request) {
                $q->where('groups.id', $request->group);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $contacts = $query->paginate(10);
        $groups = auth()->user()->groups;
        
        return view('contacts.index', compact('contacts', 'groups'));
    }

    public function create(): View
    {
        $groups = auth()->user()->groups;
        return view('contacts.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'groups' => 'array',
            'groups.*' => 'exists:groups,id',
        ]);

        $contact = auth()->user()->contacts()->create($validated);
        
        if ($request->filled('groups')) {
            $contact->groups()->sync($request->groups);
        }

        return redirect()->route('contacts.index')->with('success', 'Contact créé avec succès.');
    }

    public function show(Contact $contact): View
    {
        $this->authorize('view', $contact);
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        $this->authorize('update', $contact);
        $groups = auth()->user()->groups;
        return view('contacts.edit', compact('contact', 'groups'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'groups' => 'array',
            'groups.*' => 'exists:groups,id',
        ]);

        $contact->update($validated);
        $contact->groups()->sync($request->groups ?? []);

        return redirect()->route('contacts.index')->with('success', 'Contact mis à jour avec succès.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact supprimé avec succès.');
    }
}
