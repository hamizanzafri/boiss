<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser()
    {
        return Auth::user();
    }
    
    public function index(Request $request)
    {
        $view = $request->query('view', 'general');
        $users = User::with('roles')->get();
        $roles = Role::all();

        if ($view === 'admins') {
            return view('users.admintable', compact('users', 'roles'));
        } else {
            return view('users.usertable', compact('users', 'roles'));
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'admin' // Explicitly set user_type to admin
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->attach($role);

        return redirect()->route('users.index')->with('success', 'Admin created successfully.');
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('name', $request->input('role'))->first();

        if ($role) {
            $user->roles()->sync([$role->id]);
        }

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Update user fields...

        $user->save();

        // Redirect or return response...
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Get the roles of the user being deleted and convert them to an array of role names
        $roles = $user->roles->pluck('name')->toArray();

        // Delete the user
        $user->delete();

        // Fetch updated list of users and roles
        $users = User::with('roles')->get();
        $allRoles = Role::all();

        // Determine which view to return based on the deleted user's role
        if (in_array('admin', $roles)) {
            return redirect()->route('users.index', ['view' => 'admins'])->with('users', $users)->with('roles', $allRoles);
        } else {
            return redirect()->route('users.index', ['view' => 'general'])->with('users', $users)->with('roles', $allRoles);
        }
    }

    public function adminTable()
    {
        $users = User::where('user_type', 'admin')->get();
        return view('users.index', ['view' => 'admin', 'users' => $users]);
    }

    public function userTable()
    {
        $users = User::where('user_type', 'general')->get();
        return view('users.index', ['view' => 'general', 'users' => $users]);
    }
}
