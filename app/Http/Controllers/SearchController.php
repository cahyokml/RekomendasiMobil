<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Tampilkan halaman pencarian
     */
    public function index()
    {
        return view('cari');
    }

    /**
     * Proses pencarian mobil
     */
    public function search(Request $request)
    {
        // =========================
        // VALIDASI
        // =========================

        $request->validate([
            'harga_min'           => 'nullable|numeric|min:0',
            'harga_max'           => 'nullable|numeric|min:0',
            'jenis_bahan_bakar'  => 'required|string',
            'jumlah_kursi'       => 'required|numeric|min:1',

            // cc tidak wajib
            'kapasitas_mesin'    => 'nullable|numeric|min:0',
        ]);

        // =========================
        // AMBIL INPUT
        // =========================

        $filters = [
            'kapasitas_mesin'   => $request->kapasitas_mesin ?? 0,
            'jenis_bahan_bakar' => strtolower($request->jenis_bahan_bakar),
            'harga_min'         => $request->harga_min,
            'harga_max'         => $request->harga_max,
            'jumlah_kursi'      => $request->jumlah_kursi,
        ];

        // =========================
        // JIKA MOBIL LISTRIK
        // =========================

        if ($filters['jenis_bahan_bakar'] == 'electric') {

            // mobil listrik tidak memakai cc
            $filters['kapasitas_mesin'] = 0;
        }

        // =========================
        // PROSES PENCARIAN
        // =========================
        // logika pencarian nanti di sini

        return view('cari', [
            'filters' => $filters
        ]);
    }
}
