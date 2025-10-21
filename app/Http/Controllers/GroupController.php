<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $groups = auth()->user()->groups()->withCount('contacts')->paginate(10);
        return view('groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,NULL,id,user_id,' . auth()->id(),
            'description' => 'nullable|string',
        ]);

        auth()->user()->groups()->create($validated);
        return redirect()->route('groups.index')->with('success', 'Groupe créé avec succès.');
    }

    public function show(Group $group): View
    {
        $this->authorize('view', $group);
        $contacts = $group->contacts()->paginate(10);
        return view('groups.show', compact('group', 'contacts'));
    }

    public function edit(Group $group): View
    {
        $this->authorize('update', $group);
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $this->authorize('update', $group);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id . ',id,user_id,' . auth()->id(),
            'description' => 'nullable|string',
        ]);

        $group->update($validated);
        return redirect()->route('groups.index')->with('success', 'Groupe mis à jour avec succès.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $this->authorize('delete', $group);
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Groupe supprimé avec succès.');
    }
}
