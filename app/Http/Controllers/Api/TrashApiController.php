<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Services\TugasService;
use Illuminate\Http\Request;

class TrashApiController extends Controller
{
    protected $tugasService;

    public function __construct(TugasService $tugasService)
    {
        $this->tugasService = $tugasService;
    }

    public function index()
    {
        $trash = Tugas::onlyTrashed()
            ->with(['user', 'creator'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trash
        ]);
    }

    public function restore($id)
    {
        $tugas = Tugas::onlyTrashed()->findOrFail($id);
        $this->tugasService->restoreTugas($tugas);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikembalikan dari trash.'
        ]);
    }

    public function forceDelete($id)
    {
        $tugas = Tugas::onlyTrashed()->findOrFail($id);
        $this->tugasService->forceDeleteTugas($tugas);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus secara permanen.'
        ]);
    }
}
