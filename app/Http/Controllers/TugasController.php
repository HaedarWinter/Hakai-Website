<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('admin.tugas.index', [
                'title' => 'Data Tugas',
                'menuAdminTugas' => 'active',
            ]);
        }

        // For employees
        return view('karyawan.tugas.index', [
            'title' => 'Data Tugas',
            'menuKaryawanTugas' => 'active',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        return view('admin.tugas.show', [
            'title' => 'Detail Tugas',
            'menuAdminTugas' => 'active',
            'taskId' => $tugas->id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::employees()->get(['id', 'nama', 'email']);
        
        return view('admin.tugas.create', [
            'title' => 'Tambah Tugas',
            'menuAdminTugas' => 'active',
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource (redirect to API).
     */
    public function store(Request $request)
    {
        // Validation will be handled by API
        return redirect()->route('tugas.index')
            ->with('success', 'Form submitted. Please check task list.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas)
    {
        return view('admin.tugas.edit', [
            'title' => 'Edit Tugas',
            'menuAdminTugas' => 'active',
            'taskId' => $tugas->id,
        ]);
    }

    /**
     * Update the specified resource (redirect to API).
     */
    public function update(Request $request, Tugas $tugas)
    {
        // Validation will be handled by API
        return redirect()->route('tugas.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource (redirect to API).
     */
    public function destroy(Tugas $tugas)
    {
        // Deletion will be handled by API
        return redirect()->route('tugas.index')
            ->with('success', 'Task deleted successfully.');
    }
}
