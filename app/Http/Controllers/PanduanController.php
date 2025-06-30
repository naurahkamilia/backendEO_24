<?php

namespace App\Http\Controllers;

use App\Models\Panduan;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        $panduan = Panduan::create($validated);

        return response()->json([
            'message' => 'Panduan berhasil ditambahkan',
            'data'    => $panduan
        ], 201);
    }

    public function show()
    {
        $panduan = Panduan::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Daftar panduan',
            'data'    => $panduan
        ]);
    }
}
