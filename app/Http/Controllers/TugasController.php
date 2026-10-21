<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use App\Services\TugasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    protected $tugasService;

    public function __construct(TugasService $tugasService)
    {
        $this->tugasService = $tugasService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $employees = User::employees()->orderBy('nama')->get();

            return view('admin.tugas.index', [
                'title' => 'Data Tugas',
                'menuAdminTugas' => 'active',
                'employees' => $employees,
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
     * Display trashed tasks.
     */
    public function trash()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('admin.tugas.trash', [
                'title' => 'Sampah Tugas',
                'menuAdminTugas' => 'active',
            ]);
        }

        // For employees
        return view('karyawan.tugas.trash', [
            'title' => 'Sampah Tugas',
            'menuKaryawanTugas' => 'active',
        ]);
    }

}
