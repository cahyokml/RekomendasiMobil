@extends('layouts.app')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <section class="hero">

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <p class="hero-eyebrow">Temukan Mobil Impian Anda</p>

            <h1 class="hero-title">
                MOBIL YANG TEPAT
                UNTUK ANDA
            </h1>

            <a href="{{ route('cari') }}"
                class="bg-yellow-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-yellow-600 transition duration-300">
                CARI MOBIL →
            </a>
        </div>

    </section>
@endsection

@push('styles')
    <style>
        .hero {
            position: relative;
            width: 100vw;
            min-height: 100vh;
            /* 🔥 INI KUNCI UTAMA */
            background-image: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            margin-left: calc(-50vw + 50%);
        }

        /* Overlay gelap */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    rgba(0, 0, 0, 0.8) 0%,
                    rgba(0, 0, 0, 0.4) 50%,
                    rgba(0, 0, 0, 0.1) 100%);
            z-index: 1;
        }

        /* Konten */
        .hero-content {
            position: relative;
            z-index: 2;
            padding-left: 1%;
            max-width: 600px;
            margin-left: 8%;
        }

        .hero-eyebrow {
            color: #ccc;
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .hero-title {
            font-size: 75px;
            font-weight: 750;
            color: white;
            line-height: 1.1;
            margin-bottom: 30px;
        }

        /* Button */
        .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: white;
            color: black;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-hero:hover {
            background: #facc15;
        }

        /* Responsive */
        @media(max-width:768px) {
            .hero-title {
                font-size: 42px;
            }
        }
    </style>
@endpush
