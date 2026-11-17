<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::all();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil mengambil data siswa',
            'data' => $siswas
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:5|max:20',
            'kelas' => 'required|string|max:10',
            'jurusan' => 'required|string|max:10'
        ]);

        $siswa = Siswa::create($validated);

        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Berhasil membuat siswa',
            'data' => $siswa
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil mengambil data siswa',
            'data' => $siswa
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|string|min:5|max:20',
            'kelas' => 'sometimes|string|max:10',
            'jurusan' => 'sometimes|string|max:10'
        ]);

        $siswa->update($validated);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil mengupdate siswa',
            'data' => $siswa
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Berhasil hapus data siswa'
        ],200);
    }
}
