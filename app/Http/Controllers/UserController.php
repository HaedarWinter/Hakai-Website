<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.user.index', [
            'title' => 'Data User',
            'menuAdminUser' => 'active',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create', [
            'title' => 'Tambah User',
            'menuAdminUser' => 'active',
        ]);
    }

    /**
     * Store a newly created resource (redirect to API).
     */
    public function store(Request $request)
    {
        // Validation will be handled by API
        return redirect()->route('user.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', [
            'title' => 'Edit User',
            'menuAdminUser' => 'active',
            'userId' => $user->id,
        ]);
    }

    /**
     * Update the specified resource (redirect to API).
     */
    public function update(Request $request, User $user)
    {
        // Validation will be handled by API
        return redirect()->route('user.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource (redirect to API).
     */
    public function destroy(User $user)
    {
        // Deletion will be handled by API
        return redirect()->route('user.index')
            ->with('success', 'User deleted successfully.');
    }
}
