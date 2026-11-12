<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mobil->nama }} - Mobilku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white font-sans p-10">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-xl overflow-hidden shadow-2xl flex flex-col md:flex-row">

        <!-- Bagian Foto -->
        <div class="md:w-1/2">
            <img src="{{ $mobil->foto }}" alt="{{ $mobil->nama }}" class="w-full h-full object-cover">
        </div>

        <!-- Bagian Info -->
        <div class="p-8 md:w-1/2">
            <p class="text-blue-400 font-bold uppercase tracking-wider text-sm mb-2">{{ $mobil->merek }}</p>
            <h1 class="text-4xl font-extrabold mb-4">{{ $mobil->nama }}</h1>

            <div class="text-3xl font-bold text-green-400 mb-6">
                Rp {{ number_format($mobil->harga, 0, ',', '.') }}
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-400">Kapasitas Mesin</span>
                    <span class="font-bold">{{ $mobil->cc }} CC</span>
                </div>
                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-400">Bahan Bakar</span>
                    <span class="font-bold capitalize">{{ $mobil->bahanbakar }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-400">Jumlah Kursi</span>
                    <span class="font-bold">{{ $mobil->kursi }} Kursi</span>
                </div>
            </div>

            <a href="{{ route('cari') }}"
                class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg transition">
                ← Kembali ke Pencarian
            </a>
        </div>
    </div>
</body>

</html>
