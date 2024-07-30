<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership; // Ensure you have a Membership model

class MembershipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memberships = Membership::all();
        return view('memberships.index', compact('memberships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('memberships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $latestMembership = Membership::latest()->first();
        $nextIdNumber = $latestMembership ? ((int) substr($latestMembership->membership_id, 4) + 1) : 1;
        $newMembershipId = 'BOIS' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        $membership = new Membership;
        $membership->user_id = auth()->id(); // Ensure user is logged in or handle accordingly
        $membership->membership_id = $newMembershipId;
        $membership->name = $validated['name'];
        $membership->address = $validated['address'];
        $membership->save();

        return redirect()->route('memberships.index')->with('success', 'Membership created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $membership = Membership::findOrFail($id);
        return view('memberships.show', compact('membership'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $membership = Membership::findOrFail($id);
        return view('memberships.edit', compact('membership'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $membership = Membership::findOrFail($id);
        $membership->name = $request->name;
        $membership->address = $request->address;
        $membership->save();

        return redirect()->route('memberships.index')->with('success', 'Membership updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Membership deleted successfully.');
    }
}
