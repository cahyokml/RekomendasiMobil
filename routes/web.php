<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\ChatbotController;

// Home (halaman utama)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman pencarian
Route::get('/cari', [MobilController::class, 'index'])->name('cari');

// Proses rekomendasi
Route::post('/rekomendasi', [MobilController::class, 'rekomendasi'])->name('rekomendasi');

// Redirect jika akses GET /rekomendasi
Route::get('/rekomendasi', function () {
    return redirect()
        ->route('cari')
        ->with('warning', 'Silakan isi kriteria pencarian terlebih dahulu.');
});

// Chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

Route::get('/mobil/{id}', [MobilController::class, 'show'])->name('mobil.show');


// ROUTE SEMENTARA UNTUK TES SERVER
Route::get('/cek-server-ethio', function () {
    // 1. Cek apakah API Key terbaca
    $apiKey = config('services.openrouter.key');
    $keyStatus = $apiKey ? "Terbaca (Panjang: " . strlen($apiKey) . " karakter)" : "TIDAK TERBACA (Kosong)";

    // 2. Cek koneksi ke OpenRouter
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(15)
            ->withToken($apiKey)
            ->withOptions(['verify' => false])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemma-2-9b-it:free',
                'messages' => [['role' => 'user', 'content' => 'Halo']]
            ]);

        return "<h3>Hasil Tes Server:</h3>" .
            "<b>API Key:</b> " . $keyStatus . "<br>" .
            "<b>Koneksi Server:</b> Berhasil (Status HTTP: " . $response->status() . ")<br>" .
            "<b>Respons AI:</b> " . json_encode($response->json());
    } catch (\Exception $e) {
        return "<h3>Hasil Tes Server:</h3>" .
            "<b>API Key:</b> " . $keyStatus . "<br>" .
            "<b>Koneksi Server:</b> GAGAL/BLOKIR<br>" .
            "<b>Pesan Error:</b> " . $e->getMessage();
    }
});
