@extends('layouts.app')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hasil Rekomendasi - Mobilku</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }

            /* Rank badge colors */
            .rank-1 {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }

            .rank-2 {
                background: linear-gradient(135deg, #6b7280, #4b5563);
            }

            .rank-3 {
                background: linear-gradient(135deg, #b45309, #92400e);
            }

            .rank-other {
                background: linear-gradient(135deg, #1e3a8a, #1e40af);
            }

            /* Card animation */
            .result-card {
                animation: slideIn 0.4s ease both;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Top 3 special border */
            .top-card-1 {
                border-left: 4px solid #f59e0b;
            }

            .top-card-2 {
                border-left: 4px solid #9ca3af;
            }

            .top-card-3 {
                border-left: 4px solid #b45309;
            }

            .top-card-n {
                border-left: 4px solid #e5e7eb;
            }

            /* Progress bar */
            .bar-fill {
                height: 100%;
                border-radius: 9999px;
                transition: width 1s ease;
            }

            /* Score ring */
            .score-circle {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                flex-shrink: 0;
                position: relative;
            }

            .podium-badge {
                position: absolute;
                top: -10px;
                right: -10px;
                width: 22px;
                height: 22px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
            }

            /* hover lift */
            .result-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
                transition: all 0.25s ease;
            }

            /* Detail accordion */
            details>summary {
                list-style: none;
            }

            details>summary::-webkit-details-marker {
                display: none;
            }

            details[open] .chevron {
                transform: rotate(90deg);
            }

            .chevron {
                transition: transform 0.25s;
            }

            /* Sticky header on scroll */
            .sticky-info {
                position: sticky;
                top: 0;
                z-index: 20;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        </style>
    </head>

    {{-- ===== PAGE HEADER ===== --}}
    <div class="relative bg-gradient-to-r from-[#0f172a] via-[#1e3a8a] to-[#1e40af] text-white overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-[#FFB800]/10 rounded-full"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('cari') }}"
                        class="inline-flex items-center gap-2 text-blue-300 hover:text-white text-xs font-semibold mb-3 transition">
                        <i class="fa-solid fa-arrow-left"></i> Ubah Kriteria
                    </a>
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">
                        Hasil Rekomendasi
                        <span class="text-[#FFB800]">Mobil</span>
                    </h1>
                    <p class="text-blue-200 text-sm mt-1">Diurutkan berdasarkan skor kecocokan tertinggi</p>
                </div>
                <div
                    class="flex items-center gap-3 bg-white/10 border border-white/20 rounded-2xl px-5 py-3 backdrop-blur-sm">
                    <i class="fa-solid fa-car text-[#FFB800] text-2xl"></i>
                    <div>
                        <div class="text-3xl font-extrabold">{{ count($hasil) }}</div>
                        <div class="text-blue-200 text-xs font-semibold">Mobil Ditemukan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STICKY FILTER SUMMARY ===== --}}
    <div class="sticky-info bg-white/90 border-b border-gray-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-3">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="text-gray-500 font-semibold mr-1">Filter:</span>

                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded-full">
                    <i class="fa-solid fa-tag text-[10px]"></i>
                    Target Harga:
                    Rp {{ number_format($input['harga_target'], 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 font-bold px-3 py-1 rounded-full">
                    <i class="fa-solid fa-gear text-[10px]"></i>
                    @if (strtolower($input['bahanbakar']) != 'electric')
                        ≥ {{ $input['cc_min'] }} CC
                    @else
                        Motor Listrik
                    @endif
                </span>
                <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full">
                    <i class="fa-solid fa-gas-pump text-[10px]"></i>
                    {{ ucfirst($input['bahanbakar']) }}
                </span>
                <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-800 font-bold px-3 py-1 rounded-full">
                    <i class="fa-solid fa-users text-[10px]"></i>
                    ≥ {{ $input['kursi_min'] }} Kursi
                </span>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-6 py-8">

            @if (count($hasil) > 0)

                {{-- TOP 3 PODIUM (jika ada >= 3 hasil) --}}
                @if (count($hasil) >= 3)
                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 text-center">
                            <i class="fa-solid fa-trophy text-[#FFB800] mr-1"></i> Top 3 Terbaik
                        </p>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach (array_slice($hasil, 0, 3) as $pi => $pm)
                                @php
                                    $colors = [
                                        'from-amber-400 to-yellow-500',
                                        'from-gray-300 to-gray-400',
                                        'from-amber-700 to-amber-800',
                                    ];
                                    $labels = ['🥇 Terbaik', '🥈 Kedua', '🥉 Ketiga'];
                                    $sizes = [
                                        'md:col-span-1 order-2 md:order-1',
                                        'md:col-span-1 order-1 md:order-2',
                                        'md:col-span-1 order-3',
                                    ];
                                @endphp
                                <div
                                    class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 text-center
                        {{ $pi === 0 ? 'ring-2 ring-[#FFB800]' : '' }}">
                                    {{-- car image --}}
                                    <div class="h-32 overflow-hidden relative">
                                        <img src="{{ $pm['foto'] }}" alt="{{ $pm['merek'] }}"
                                            class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                        <span class="absolute top-2 left-2 text-lg">
                                            {{ ['🥇', '🥈', '🥉'][$pi] }}
                                        </span>
                                        <div
                                            class="absolute bottom-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            {{ $pm['persen'] }}%
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <p class="text-xs text-gray-500 font-semibold">{{ $pm['merek'] }}</p>
                                        <p class="font-extrabold text-gray-800 text-sm leading-tight">{{ $pm['nama'] }}
                                        </p>
                                        <p class="text-[#FFB800] font-bold text-xs mt-1">{{ $pm['harga_fmt'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ===== ALL RESULTS LIST ===== --}}
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-list mr-1"></i> Semua Hasil
                </p>

                <div class="space-y-4">
                    @foreach ($hasil as $index => $mobil)
                        @php
                            $rank = $index + 1;
                            $rankClass = match (true) {
                                $rank === 1 => 'rank-1',
                                $rank === 2 => 'rank-2',
                                $rank === 3 => 'rank-3',
                                default => 'rank-other',
                            };
                            $cardBorder = match (true) {
                                $rank === 1 => 'top-card-1',
                                $rank === 2 => 'top-card-2',
                                $rank === 3 => 'top-card-3',
                                default => 'top-card-n',
                            };
                            $scoreColor = match (true) {
                                $mobil['persen'] >= 80 => 'text-green-600 bg-green-50',
                                $mobil['persen'] >= 50 => 'text-yellow-600 bg-yellow-50',
                                default => 'text-red-500 bg-red-50',
                            };
                            $barColor = match (true) {
                                $mobil['persen'] >= 80 => 'bg-green-500',
                                $mobil['persen'] >= 50 => 'bg-yellow-400',
                                default => 'bg-red-400',
                            };
                            $animDelay = $index * 60;
                        @endphp

                        <div class="result-card bg-white rounded-2xl shadow-sm overflow-hidden {{ $cardBorder }}"
                            style="animation-delay: {{ $animDelay }}ms">
                            <div class="flex flex-col md:flex-row">

                                {{-- Car Image --}}
                                <div
                                    class="w-full md:w-52 h-44 md:h-auto flex-shrink-0 relative overflow-hidden bg-gray-100">
                                    <img src="{{ $mobil['foto'] }}" alt="{{ $mobil['merek'] }} {{ $mobil['nama'] }}"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-500"
                                        onerror="this.src='https://loremflickr.com/400/300/car?random={{ $index }}'">
                                    {{-- Rank overlay on image --}}
                                    <div class="absolute top-3 left-3">
                                        <div
                                            class="{{ $rankClass }} text-white text-xs font-extrabold px-2.5 py-1.5 rounded-lg shadow-md">
                                            #{{ $rank }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 p-5">

                                    {{-- Header Row --}}
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                                {{ $mobil['merek'] }}</p>
                                            <h3 class="text-lg font-extrabold text-gray-900 leading-tight truncate">
                                                {{ $mobil['nama'] }}</h3>
                                            <p class="text-[#FFB800] font-bold text-base mt-0.5">{{ $mobil['harga_fmt'] }}
                                            </p>
                                        </div>

                                        {{-- Score circle --}}
                                        <div
                                            class="score-circle {{ $scoreColor }} border-4
                                {{ $mobil['persen'] >= 80 ? 'border-green-200' : ($mobil['persen'] >= 50 ? 'border-yellow-200' : 'border-red-200') }}">
                                            <span class="text-xl font-extrabold leading-none">{{ $mobil['persen'] }}</span>
                                            <span class="text-[9px] font-bold opacity-70">%</span>
                                            @if ($rank <= 3)
                                                <div class="podium-badge {{ $rankClass }} text-white shadow">
                                                    {{ ['🥇', '🥈', '🥉'][$rank - 1] ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-4">
                                        <div class="flex justify-between text-[10px] font-semibold text-gray-400 mb-1">
                                            <span>Kecocokan</span>
                                            <span>{{ $mobil['persen'] }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="bar-fill {{ $barColor }}"
                                                style="width: {{ $mobil['persen'] }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Spec Pills --}}
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full border border-orange-100">
                                            <i class="fa-solid fa-users text-[10px]"></i> {{ $mobil['kursi'] }} Kursi
                                        </span>
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-100">
                                            <i class="fa-solid fa-gas-pump text-[10px]"></i>
                                            {{ ucfirst($mobil['bahanbakar']) }}
                                        </span>
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full border border-blue-100">
                                            <i class="fa-solid fa-gear text-[10px]"></i>
                                            @if (strtolower($mobil['bahanbakar']) != 'electric')
                                                {{ $mobil['cc'] }} CC
                                            @else
                                                Electric
                                            @endif
                                        </span>
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-full border border-purple-100">
                                            <i class="fa-solid fa-star text-[10px]"></i> Skor
                                            {{ round($mobil['skor'] * 100, 1) }}
                                        </span>
                                    </div>

                                    {{-- Expandable Detail Scores --}}
                                    <details class="group">
                                        <summary
                                            class="flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#1e3a8a] cursor-pointer select-none transition py-1">
                                            <i class="fa-solid fa-chevron-right chevron text-[10px]"></i>
                                            Detail Penilaian Per Kriteria
                                        </summary>

                                        <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                                                {{-- Harga --}}
                                                <div class="bg-gray-50 rounded-xl p-3">
                                                    <div class="flex items-center gap-1.5 mb-2">
                                                        <i class="fa-solid fa-tag text-blue-500 text-xs"></i>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Harga</span>
                                                    </div>
                                                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden mb-1">
                                                        <div class="h-full bg-blue-500 rounded-full"
                                                            style="width: {{ $mobil['s_harga'] * 100 }}%"></div>
                                                    </div>
                                                    <p class="text-sm font-extrabold text-gray-800">
                                                        {{ round($mobil['s_harga'] * 100) }}%</p>
                                                </div>

                                                {{-- Mesin --}}
                                                <div class="bg-gray-50 rounded-xl p-3">
                                                    <div class="flex items-center gap-1.5 mb-2">
                                                        <i class="fa-solid fa-gear text-green-500 text-xs"></i>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Mesin</span>
                                                    </div>
                                                    <div
                                                        class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden mb-1">
                                                        <div class="h-full bg-green-500 rounded-full"
                                                            style="width: {{ $mobil['s_cc'] * 100 }}%"></div>
                                                    </div>
                                                    <p class="text-sm font-extrabold text-gray-800">
                                                        {{ round($mobil['s_cc'] * 100) }}%</p>
                                                </div>

                                                {{-- Bahan Bakar --}}
                                                <div class="bg-gray-50 rounded-xl p-3">
                                                    <div class="flex items-center gap-1.5 mb-2">
                                                        <i class="fa-solid fa-gas-pump text-orange-500 text-xs"></i>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Bahan
                                                            Bakar</span>
                                                    </div>
                                                    <div
                                                        class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden mb-1">
                                                        <div class="h-full bg-orange-500 rounded-full"
                                                            style="width: {{ $mobil['s_fuel'] * 100 }}%"></div>
                                                    </div>
                                                    <p class="text-sm font-extrabold text-gray-800">
                                                        {{ $mobil['s_fuel'] == 1 ? '100%' : '0%' }}</p>
                                                </div>

                                                {{-- Kursi --}}
                                                <div class="bg-gray-50 rounded-xl p-3">
                                                    <div class="flex items-center gap-1.5 mb-2">
                                                        <i class="fa-solid fa-users text-red-500 text-xs"></i>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Kursi</span>
                                                    </div>
                                                    <div
                                                        class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden mb-1">
                                                        <div class="h-full bg-red-500 rounded-full"
                                                            style="width: {{ $mobil['s_kursi'] * 100 }}%"></div>
                                                    </div>
                                                    <p class="text-sm font-extrabold text-gray-800">
                                                        {{ round($mobil['s_kursi'] * 100) }}%</p>
                                                </div>

                                            </div>

                                            {{-- Formula note --}}
                                            <div class="mt-3 bg-blue-50 rounded-lg px-3 py-2 flex items-center gap-2">
                                                <i class="fa-solid fa-calculator text-blue-400 text-xs"></i>
                                                <p class="text-[10px] text-blue-700 font-semibold">
                                                    Skor = (0.40 × Similarity Harga) + (0.30 × Similarity Kursi) + (0.20 ×
                                                    Similarity Bahan Bakar) + (0.10 × Similarity CC) =
                                                    <strong>{{ round($mobil['skor'] * 100, 2) }}</strong>
                                                </p>
                                            </div>
                                            <div class="mt-3 bg-blue-50 rounded-xl p-3 text-xs text-blue-700 space-y-2">

                                                <p>
                                                    <strong>Perhitungan Harga:</strong><br>
                                                    {{ $mobil['detail_harga'] }}
                                                </p>

                                                <p>
                                                    <strong>Total Similarity:</strong><br>
                                                    {{ $mobil['detail_total'] }}
                                                </p>

                                            </div>
                                        </div>
                                    </details>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Back Button --}}
                <div class="mt-10 text-center">
                    <a href="{{ route('cari') }}"
                        class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-xl shadow transition duration-200">
                        <i class="fa-solid fa-arrow-left"></i> Cari Mobil Lainnya
                    </a>
                </div>
            @else
                {{-- ===== EMPTY STATE ===== --}}
                <div class="max-w-md mx-auto text-center py-20">
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-car-burst text-yellow-500 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                        Tidak ada mobil yang cocok dengan kriteria Anda.<br>
                        Coba perluas rentang harga atau ubah spesifikasi.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('cari') }}"
                            class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-3 px-6 rounded-xl transition">
                            <i class="fa-solid fa-sliders mr-2"></i> Ubah Kriteria
                        </a>
                        <a href="{{ route('home') }}"
                            class="bg-white border-2 border-gray-200 hover:border-[#FFB800] text-gray-800 font-bold py-3 px-6 rounded-xl transition">
                            <i class="fa-solid fa-house mr-2"></i> Kembali
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const fuelSelect = document.querySelector('select[name="bahanbakar"]');
            const ccInput = document.getElementById('cc_min');

            function toggleCC() {

                if (fuelSelect.value.toLowerCase() === 'electric') {

                    ccInput.value = '';
                    ccInput.disabled = true;
                    ccInput.placeholder = 'Mobil listrik tidak memakai CC';

                } else {

                    ccInput.disabled = false;
                    ccInput.placeholder = 'Contoh: 1500';
                }
            }

            fuelSelect.addEventListener('change', toggleCC);

            toggleCC();
        });
    </script>

@endsection
