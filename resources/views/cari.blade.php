@extends('layouts.app')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mobilku - Cari Mobil</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }

            /* ── Brand card ── */
            .brand-card {
                position: relative;
                overflow: hidden;
                border-radius: 18px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .brand-card:hover {
                transform: translateY(-4px);
            }

            .brand-card.active .checkmark {
                opacity: 1 !important;
                transform: scale(1) !important;
            }

            .brand-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .brand-label {
                position: absolute;
                bottom: 10px;
                left: 12px;
                color: white;
                z-index: 2;
                font-size: 14px;
                font-weight: 700;
            }

            .checkmark {
                position: absolute;
                top: 8px;
                right: 8px;
                z-index: 3;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: #FFB800;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                color: #1a1a1a;
                font-weight: 900;
                opacity: 0;
                transform: scale(0.5);
                transition: opacity 0.25s, transform 0.25s;
            }

            /* Form focus ring custom */
            .input-field {
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .input-field:focus {
                outline: none;
                border-color: #1e3a8a;
                box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.12);
            }

            /* Harga preview badge */
            #harga-preview {
                transition: all 0.2s;
            }

            /* Active merek indicator di form */
            #merek-aktif-label {
                transition: all 0.3s;
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top,
                        rgba(0, 0, 0, 0.8),
                        rgba(0, 0, 0, 0.1));
            }

            .custom-pagination nav {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .custom-pagination nav>div>span {
                background: white !important;
                color: #6b7280 !important;
                border: 1px solid #e5e7eb !important;
            }

            .custom-pagination a[rel="prev"],
            .custom-pagination a[rel="next"] {
                background: white !important;
                color: #6b7280 !important;
            }

            .custom-pagination svg {
                width: 14px;
                height: 14px;
            }

            .custom-pagination .hidden.sm\\:flex-1 {
                display: none;
            }

            /* sembunyikan tulisan Previous dan Next */
            .custom-pagination span[aria-current],
            .custom-pagination a,
            .custom-pagination span {
                border-radius: 10px;
                font-size: 13px;
                min-width: 34px;
                height: 34px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                transition: all .2s ease;
                color: #6b7280;
                background: white;
            }

            /* active page */
            .custom-pagination span[aria-current="page"] span {
                background: #1e3a8a !important;
                color: white !important;
                border: none !important;
            }

            /* normal button */
            .custom-pagination a {
                background: white;
                color: #6b7280;
                border: 1px solid #e5e7eb;
            }

            /* hover */
            .custom-pagination a:hover {
                background: #FFB800;
                /* color: #111827; */
                border-color: #FFB800;
            }

            /* hilangkan text panjang */
            .custom-pagination p {
                display: none;
            }
        </style>
    </head>

    <body class="bg-white text-gray-800">

        {{-- ══════════════════════════════════════
         HERO + NAVBAR (struktur asli dipertahankan)
         ══════════════════════════════════════ --}}
        <div class="relative w-full h-[600px] bg-gray-900">
            <img src="https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                alt="Hero Car" class="absolute inset-0 w-full h-full object-cover opacity-80">

            <div class="relative z-10 flex justify-between items-center px-10 py-4">
                <a href="{{ route('home') }}"
                    class="text-3xl font-extrabold text-[#1e3a8a] tracking-tight hover:opacity-80">
                    Mobilku
                </a>
                <div class="flex items-center space-x-6 text-sm font-semibold text-gray-800">
                    <div class="flex space-x-4 text-lg">
                        <a href="#" class="hover:text-blue-600"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="relative z-10 bg-white/40 backdrop-blur-md border-y border-white/20">
                <div
                    class="max-w-6xl mx-auto flex justify-between space-x-11 py-3 text-sm font-bold text-gray-900 tracking-wider">
                    <a href="{{ route('home') }}" class="hover:text-blue-800">HOME</a>
                    <a href="{{ route('cari') }}" class="text-[#1e3a8a] border-b-2 border-[#FFB800]">PENCARIAN</a>
                    {{-- <a href="#" class="hover:text-blue-800">TENTANG KAMI</a> --}}
                    <a href="{{ route('rekomendasi') }}" class="hover:text-blue-800">REKOMENDASI </a>

                </div>
                @if (session('warning'))
                    <div class="max-w-6xl mx-auto mt-4 px-4">

                        <div
                            class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl shadow-sm">

                            <div class="mt-0.5">
                                <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                            </div>

                            <div>
                                <p class="font-bold text-sm">
                                    Peringatan
                                </p>

                                <p class="text-sm">
                                    {{ session('warning') }}
                                </p>
                            </div>

                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- ══════════════════════════════════════
         LANGKAH 1 — PILIH MEREK
         ══════════════════════════════════════ --}}
        <div class="bg-[#111827] py-10 px-6">
            <div class="max-w-6xl mx-auto">

                {{-- Header langkah --}}
                <div class="flex items-start gap-3 mb-7 flex-wrap md:flex-nowrap">

                    {{-- Step --}}
                    <div
                        class="w-8 h-8 rounded-full bg-[#FFB800] text-gray-900 font-extrabold text-sm flex items-center justify-center flex-shrink-0">
                        1
                    </div>

                    {{-- Title --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-base">
                            Pilih Preferensi Merek (Opsional)
                        </p>

                        <p class="text-gray-400 text-xs leading-relaxed">
                            Pilih merek favorit Anda atau lewati untuk melihat semua rekomendasi terbaik
                        </p>
                    </div>

                    {{-- Active Brand --}}
                    <div id="merek-aktif-label"
                        class="bg-[#FFB800] text-gray-900 text-xs font-bold px-4 py-1.5 rounded-full hidden whitespace-nowrap">

                        <i class="fa-solid fa-check mr-1"></i>

                        <span id="merek-aktif-text">
                            Semua Merek
                        </span>
                    </div>

                </div>

                {{-- Brand Slider --}}
                <div id="brand-grid"
                    class="flex gap-4 overflow-x-auto pb-3 scrollbar-hide scroll-smooth snap-x snap-mandatory">

                    {{-- Semua Merek --}}
                    <div class="brand-card active flex-shrink-0 w-32 h-24 md:w-36 md:h-28 snap-start" data-merek="semua"
                        title="Semua Merek">

                        <div
                            class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex flex-col items-center justify-center gap-1 rounded-2xl">

                            <i class="fa-solid fa-border-all text-[#FFB800] text-2xl"></i>
                        </div>

                        <div class="overlay"></div>

                        <div class="brand-label">
                            <p>Semua</p>
                        </div>

                        <div class="checkmark">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>

                    {{-- Brand Dinamis --}}
                    @foreach ($brands as $brand)
                        <div class="brand-card flex-shrink-0 w-32 h-24 md:w-36 md:h-28 snap-start"
                            data-merek="{{ $brand->merek }}" title="{{ $brand->merek }}">

                            <img src="{{ $brand->foto ?: 'https://placehold.co/300x200?text=No+Image' }}"
                                alt="{{ $brand->merek }}" class="w-full h-full object-cover rounded-2xl"
                                onerror="this.src='https://placehold.co/300x200/1e3a8a/ffffff?text={{ urlencode($brand->merek) }}'">

                            <div class="overlay"></div>

                            <div class="brand-label">
                                <p>{{ $brand->merek }}</p>
                            </div>

                            <div class="checkmark">
                                <i class="fa-solid fa-check"></i>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════
         LANGKAH 2 — ISI ATRIBUT & CARI
         ══════════════════════════════════════ --}}
        <div class="bg-gray-100 py-8 border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4">

                {{-- Header langkah --}}
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-8 h-8 rounded-full bg-[#1e3a8a] text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0">
                        2
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-base">Isi Kriteria Pencarian</p>
                        <p class="text-gray-400 text-xs">Sistem akan meranking mobil berdasarkan kedekatan dengan kriteria
                            Anda</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('rekomendasi') }}" id="search-form">
                    @csrf
                    <input type="hidden" name="merek" id="input-merek" value="{{ request('merek', 'semua') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-stretch">

                        {{-- ── HARGA TARGET (SATU NILAI) ── --}}
                        <div
                            class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 h-full flex flex-col justify-between">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">
                                <i class="fa-solid fa-tag text-[#FFB800] mr-1"></i> Harga Target (Rp)
                            </label>
                            <p class="text-[10px] text-gray-400 mb-2">Masukkan harga yang paling ideal untuk Anda</p>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">Rp</span>
                                <input type="number" name="harga_target" id="harga_target" placeholder="Contoh: 300000000"
                                    value="{{ old('harga_target') }}" min="1"
                                    class="input-field w-full pl-9 pr-3 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-semibold"
                                    required>
                            </div>
                            {{-- Preview format rupiah --}}
                            <p id="harga-preview" class="text-xs text-[#1e3a8a] font-bold mt-1.5 min-h-[18px]"></p>
                        </div>

                        {{-- ── CC MINIMUM ── --}}
                        <div id="cc-wrapper"
                            class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 h-full flex flex-col justify-between">

                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">
                                <i class="fa-solid fa-gear text-blue-500 mr-1"></i> Kapasitas Mesin
                            </label>

                            <p class="text-[10px] text-gray-400 mb-2">
                                Cari mobil dengan CC minimum
                            </p>

                            <div class="relative">

                                <input type="number" name="cc_min" id="cc_min" placeholder="Contoh: 1500"
                                    value="{{ old('cc_min') }}" min="0"
                                    class="input-field w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-semibold">

                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">
                                    CC
                                </span>

                            </div>

                            {{-- Keterangan khusus listrik --}}
                            <p id="cc-info" class="hidden text-[10px] text-green-600 font-semibold mt-2">
                                Mobil listrik tidak menggunakan CC
                            </p>

                        </div>

                        {{-- ── BAHAN BAKAR ── --}}
                        <div
                            class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 h-full flex flex-col justify-between">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">
                                <i class="fa-solid fa-gas-pump text-green-600 mr-1"></i> Bahan Bakar
                            </label>
                            <p class="text-[10px] text-gray-400 mb-2">Jenis bahan bakar yang diinginkan</p>
                            <select name="bahanbakar" id="bahanbakar"
                                class="input-field w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-semibold bg-white"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="bensin" {{ old('bahanbakar') == 'bensin' ? 'selected' : '' }}>⛽ Bensin
                                </option>
                                <option value="disel" {{ old('bahanbakar') == 'disel' ? 'selected' : '' }}>🛢️ Diesel
                                </option>
                                <option value="electric" {{ old('bahanbakar') == 'electric' ? 'selected' : '' }}>⚡ Listrik
                                </option>
                                <option value="hybrid" {{ old('bahanbakar') == 'hybrid' ? 'selected' : '' }}>🔋 Hybrid
                                </option>
                            </select>
                        </div>

                        {{-- ── KURSI MINIMUM ── --}}
                        <div
                            class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 h-full flex flex-col justify-between">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">
                                <i class="fa-solid fa-users text-orange-500 mr-1"></i> Min. Kursi
                            </label>
                            <p class="text-[10px] text-gray-400 mb-2">Jumlah kursi minimal yang dibutuhkan</p>
                            <select name="kursi_min"
                                class="input-field w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-semibold bg-white"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="2" {{ old('kursi_min') == 2 ? 'selected' : '' }}>2 Kursi</option>
                                <option value="4" {{ old('kursi_min') == 4 ? 'selected' : '' }}>4 Kursi</option>
                                <option value="5" {{ old('kursi_min') == 5 ? 'selected' : '' }}>5 Kursi</option>
                                <option value="6" {{ old('kursi_min') == 6 ? 'selected' : '' }}>6 Kursi</option>
                                <option value="7" {{ old('kursi_min') == 7 ? 'selected' : '' }}>7 Kursi</option>
                                <option value="8" {{ old('kursi_min') == 8 ? 'selected' : '' }}>8 Kursi</option>
                            </select>
                        </div>

                        {{-- ── TOMBOL CARI ── --}}
                        <div class="h-full flex items-end">
                            <button type="submit"
                                class="bg-[#FFB800] hover:bg-yellow-500 active:bg-yellow-600 text-gray-900 font-bold py-3 px-8 rounded-xl text-sm transition duration-200 shadow-md hover:shadow-lg flex items-center gap-2 whitespace-nowrap">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Cari Rekomendasi
                            </button>
                        </div>

                    </div>

                    {{-- Info metode yang digunakan --}}
                    <div
                        class="mt-4 bg-blue-50 border border-blue-200 rounded-lg px-4 py-2.5 inline-flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500 text-xs"></i>
                        <p class="text-xs text-blue-700">
                            <strong>Metode:</strong> Constraint-Based Scoring — Skor dihitung berdasarkan pembobotan
                            atribut dengan komposisi:
                            40% harga, 30% kapasitas kursi, 20% jenis bahan bakar, dan 10% kapasitas mesin (CC). Semakin
                            tinggi nilai similarity, maka semakin sesuai mobil dengan preferensi pengguna.
                        </p>
                    </div>

                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════
         DAFTAR MOBIL (preview)
         ══════════════════════════════════════ --}}
        <div class="max-w-6xl mx-auto py-10 px-4">
            <div class="flex justify-between items-center border-b-2 border-gray-200 pb-2 mb-6">
                <h2 class="text-xl font-bold text-[#1e3a8a] border-b-4 border-[#FFB800] pb-2 -mb-[4px]">
                    Mobil Tersedia
                    @if (request('merek') && request('merek') !== 'semua')
                        <span class="text-gray-400 font-normal text-base ml-1">— {{ request('merek') }}</span>
                    @endif
                </h2>
                <div class="flex items-center gap-4">

                    {{-- Total mobil --}}
                    <span class="text-sm font-semibold text-gray-500 whitespace-nowrap">
                        {{ $cars->total() }} Mobil
                    </span>

                    {{-- Pagination --}}
                    <div class="custom-pagination">
                        {{ $cars->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>

            @if ($cars->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($cars as $car)
                        <div
                            class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300 group cursor-pointer border-t-4 border-[#FFB800]">
                            <div class="h-40 overflow-hidden bg-gray-100">
                                <img src="{{ $car->foto }}" alt="{{ $car->merek }} {{ $car->nama }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    onerror="this.onerror=null;this.src='https://placehold.co/400x300/1e3a8a/ffffff?text={{ urlencode($car->merek) }}'">
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                    {{ $car->merek }}</p>
                                <h3 class="text-sm font-bold text-gray-800 mb-1">{{ $car->nama }}</h3>
                                <p class="text-lg font-bold text-[#FFB800] mb-2">{{ $car->harga_format }}</p>
                                <div class="grid grid-cols-3 gap-2 mb-3 text-xs">
                                    <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-center font-semibold">
                                        {{ $car->cc }} CC
                                    </div>
                                    <div class="bg-green-100 text-green-800 px-2 py-1 rounded text-center font-semibold">
                                        {{ $car->bahanbakar_label }}
                                    </div>
                                    <div class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-center font-semibold">
                                        {{ $car->kursi }} Kursi
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="flex justify-center mt-8 mb-4">{{ $cars->links() }}</div> --}}
            @else
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-12 text-center">
                    <i class="fa-solid fa-car text-yellow-500 text-5xl mb-4 block"></i>
                    <h3 class="text-xl font-bold text-yellow-800 mb-2">Tidak Ada Data Mobil</h3>
                    <p class="text-yellow-700">Silakan jalankan seeder untuk menambahkan data mobil.</p>
                </div>
            @endif
        </div>

        <!-- FOOTER BANNER -->
        <div class="w-full h-[300px]">
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                alt="Footer Banner" class="w-full h-full object-cover">
        </div>

    </body>

    {{-- ══════════════════════════════════════
     JAVASCRIPT — Brand picker + Harga preview
     ══════════════════════════════════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ── 1. Brand picker ──────────────────────────────────
            const cards = document.querySelectorAll('#brand-grid .brand-card');
            const inputMerek = document.getElementById('input-merek');
            const labelWrap = document.getElementById('merek-aktif-label');
            const labelText = document.getElementById('merek-aktif-text');

            // Baca merek dari URL (?merek=Toyota)
            const currentMerek = new URLSearchParams(window.location.search).get('merek') || 'semua';

            // Set active state & label sesuai URL
            cards.forEach(c => {
                if (c.dataset.merek === currentMerek) {
                    c.classList.add('active');
                } else {
                    c.classList.remove('active');
                }
            });

            inputMerek.value = currentMerek;
            labelText.textContent = currentMerek === 'semua' ? 'Semua Merek' : currentMerek;
            labelWrap.classList.remove('hidden');

            // Klik brand → redirect dengan ?merek=...
            cards.forEach(card => {
                card.addEventListener('click', () => {
                    const merek = card.dataset.merek;
                    const url = new URL(window.location.href);
                    url.searchParams.set('merek', merek);
                    window.location.href = url.toString();
                });
            });

            // ── 2. Harga preview (format Rupiah real-time) ───────
            const hargaInput = document.getElementById('harga_target');
            const hargaPreview = document.getElementById('harga-preview');

            function formatRupiah(angka) {
                if (!angka || isNaN(angka)) return '';
                return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
            }

            hargaInput.addEventListener('input', () => {
                hargaPreview.textContent = formatRupiah(hargaInput.value);
            });

            if (hargaInput.value) {
                hargaPreview.textContent = formatRupiah(hargaInput.value);
            }
        });

        // ── 3. Toggle CC jika listrik ─────────────────────────────
        const fuelSelect = document.getElementById('bahanbakar');
        const ccWrapper = document.getElementById('cc-wrapper');
        const ccInput = document.getElementById('cc_min');
        const ccInfo = document.getElementById('cc-info');

        function toggleCCField() {
            const fuel = fuelSelect.value.toLowerCase();
            if (fuel === 'electric') {
                ccInput.value = '';
                ccInput.required = false;
                ccInput.disabled = true;
                ccWrapper.classList.add('opacity-60');
                ccInfo.classList.remove('hidden');
            } else {
                ccInput.disabled = false;
                ccInput.required = true;
                ccWrapper.classList.remove('opacity-60');
                ccInfo.classList.add('hidden');
            }
        }

        toggleCCField();
        fuelSelect.addEventListener('change', toggleCCField);
    </script>

@endsection
