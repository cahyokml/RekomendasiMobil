<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');

        // 1. Ambil data mobil relevan dari database
        $mobilRelevan = $this->cariMobilRelevan($userMessage);

        // 2. Ubah data mobil menjadi teks untuk prompt
        // 2. Ubah data mobil menjadi teks untuk prompt (TAMBAHKAN LINK)
        $konteksMobil = $mobilRelevan->map(function ($m) {
            $url = route('mobil.show', $m->id); // <-- Pastikan ini ada

            return "- [{$m->nama}]({$url}) ({$m->merek}): "
                . "Harga Rp" . number_format($m->harga, 0, ',', '.') . ", "
                . "{$m->kursi} kursi, "
                . "bahan bakar {$m->bahanbakar}, "
                . "mesin {$m->cc}cc";
        })->implode("\n");

        // 3. Susun prompt
        $systemPrompt = "Anda adalah asisten rekomendasi mobil untuk aplikasi Mobilku. "
            . "Jawablah HANYA berdasarkan data mobil berikut yang tersedia di database:\n\n"
            . ($konteksMobil ?: "Tidak ada mobil yang cocok dengan kriteria pengguna.")
            . "\n\nATURAN WAJIB:\n"
            . "- Jangan mengarang mobil yang tidak ada di daftar.\n"
            . "- JIKA merekomendasikan mobil, WAJIB gunakan format link yang PERSIS seperti yang tertera pada data di atas. JANGAN MENGUBAH URL SAMA SEKALI.\n"
            . "- Contoh format yang BENAR: [Toyota Avanza](http://127.0.0.1:8000/mobil/5)\n"
            . "- Contoh format yang SALAH: [Toyota Avanza](#) atau Toyota Avanza\n"
            . "- Jika tidak ada yang cocok, katakan dengan jujur.\n"
            . "- Jawab dengan ramah dan ringkas.";

        // 4. Kirim ke OpenRouter
        $response = Http::withToken(env('OPENROUTER_API_KEY'))
            ->withOptions(['verify' => false])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => env('OPENROUTER_MODEL'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

        if ($response->successful()) {
            return response()->json([
                'reply' => $response->json('choices.0.message.content'),
                'jumlah_mobil_dianalisis' => $mobilRelevan->count(),
            ]);
        }

        return response()->json([
            'reply' => 'Maaf, asisten AI sedang tidak dapat dihubungi.',
            'error' => $response->json(),
        ], 500);
    }

    private function cariMobilRelevan(string $pesan)
    {
        $query = Mobil::query();

        // Deteksi budget: "200 juta" / "200jt"
        if (preg_match('/(\d+)\s*(juta|jt)/i', $pesan, $m)) {
            $query->where('harga', '<=', (int)$m[1] * 1000000);
        }

        // Deteksi jumlah kursi
        if (preg_match('/(\d+)\s*(kursi|penumpang)/i', $pesan, $m)) {
            $query->where('kursi', '>=', (int)$m[1]);
        }

        // Deteksi bahan bakar
        if (stripos($pesan, 'bensin') !== false) {
            $query->where('bahanbakar', 'like', '%bensin%');
        }
        if (stripos($pesan, 'diesel') !== false) {
            $query->where('bahanbakar', 'like', '%diesel%');
        }

        return $query->take(10)->get();
    }
}
