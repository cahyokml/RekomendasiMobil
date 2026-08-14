<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $merek = request('merek', 'semua');

        $cars = Mobil::when($merek && $merek !== 'semua', function ($q) use ($merek) {
            $q->where('merek', $merek);
        })
            ->orderBy('merek')
            ->paginate(8);

        $brands = Mobil::all()
            ->groupBy(function ($item) {
                return strtolower(trim($item->merek));
            })
            ->map(function ($items) {
                return $items->first();
            })
            ->values();

        return view('cari', compact('cars', 'brands'));
    }

    public function rekomendasi(Request $request)
    {
        $request->validate([
            'harga_target' => 'required|numeric|min:1',
            'bahanbakar'   => 'required|string',
            'kursi_min'    => 'required|numeric|min:1',
            'merek'        => 'nullable|string',
            'cc_min'       => 'nullable|numeric|min:0',
        ]);

        $input = [
            'harga_target' => $request->harga_target,
            'cc_min'       => $request->cc_min ?? 0,
            'bahanbakar'   => $request->bahanbakar,
            'kursi_min'    => $request->kursi_min,
            'merek'        => $request->merek ?? 'semua',
        ];

        $fuel = strtolower(trim($input['bahanbakar']));

        if ($fuel === 'electric') {
            $input['cc_min'] = 0;
        }

        // QUERY dengan filter merek 
        $query = Mobil::query();

        if (!empty($input['merek']) && $input['merek'] !== 'semua') {
            $query->where('merek', $input['merek']);
        }
        $query->where('harga', '<=', $input['harga_target']);
        $query->where('kursi', '>=', $input['kursi_min']);
        $query->whereRaw('LOWER(bahanbakar) = ?', [$fuel]);

        if ($fuel !== 'electric') {
            $query->where('cc', '>=', $input['cc_min']);
        }

        $mobils = $query->get();

        // echo "Jumlah mobil lolos filtering : " . $mobils->count();
        // die();


        if ($mobils->isEmpty()) {
            return view('mobil.hasil', [
                'hasil' => [],
                'input' => $input,
            ]);
        }

        $hasil = [];

        foreach ($mobils as $mobil) {

            $targetHarga = (float) $input['harga_target'];
            $hargaMobil  = (float) $mobil->harga;
            $ccUser      = (float) $input['cc_min'];
            $ccMobil     = (float) $mobil->cc;
            $kursiUser   = (float) $input['kursi_min'];
            $kursiMobil  = (float) $mobil->kursi;

            // Similarity harga
            $s_harga = 1 - (
                abs($hargaMobil - $targetHarga) / max($targetHarga, $hargaMobil)
            );

            $s_harga = max(0, min(1, $s_harga));

            // Similarity CC
            if ($fuel === 'electric') {
                $s_cc = 1;
            } elseif ($ccUser <= 0 || $ccMobil <= 0) {
                $s_cc = 0;
            } else {
                $s_cc = 1 - abs($ccMobil - $ccUser) / max($ccMobil, $ccUser);
            }

            // Similarity fuel
            $s_fuel = strtolower($mobil->bahanbakar) === $fuel ? 1 : 0;

            // Similarity kursi
            if ($kursiUser <= 0 || $kursiMobil <= 0) {
                $s_kursi = 0;
            } else {
                $s_kursi = 1 - abs($kursiMobil - $kursiUser) / max($kursiMobil, $kursiUser);
            }

            $skor =
                (0.25 * $s_harga) +
                (0.25 * $s_cc) +
                (0.25 * $s_fuel) +
                (0.25 * $s_kursi);

            $hasil[] = [
                'id'         => $mobil->id,
                'merek'      => $mobil->merek,
                'nama'       => $mobil->nama,
                'harga'      => $mobil->harga,
                'harga_fmt'  => $mobil->harga_format,
                'cc'         => $mobil->cc,
                'bahanbakar' => $mobil->bahanbakar_label,
                'kursi'      => $mobil->kursi,
                'foto'       => $mobil->foto,

                's_harga' => round($s_harga, 3),
                's_cc'    => round($s_cc, 3),
                's_fuel'  => round($s_fuel, 3),
                's_kursi' => round($s_kursi, 3),
                'skor'    => round($skor, 3),
                'persen'  => round($skor * 100, 2),

                'detail_harga' =>
                "1 - (|" . number_format($hargaMobil, 0, ',', '.') .
                    " - " . number_format($targetHarga, 0, ',', '.') .
                    "| / " . number_format($targetHarga, 0, ',', '.') .
                    ") = " . round($s_harga, 3),

                'detail_total' =>
                "(0.25 x " . round($s_harga, 3) . ") + " .
                    "(0.25 x " . round($s_cc, 3) . ") + " .
                    "(0.25 x " . round($s_fuel, 3) . ") + " .
                    "(0.25 x " . round($s_kursi, 3) . ") = " .
                    round($skor, 3),
            ];
        }

        usort($hasil, function ($a, $b) {
            if ($b['skor'] != $a['skor']) return $b['skor'] <=> $a['skor'];
            return $a['harga'] <=> $b['harga'];
        });

        $hasil = array_slice($hasil, 0, 10);

        return view('mobil.hasil', compact('hasil', 'input'));
    }
}
