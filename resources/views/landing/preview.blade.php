@extends('layouts.app')

@section('title', 'Preview PDF - Tabel Harga')

@section('content')
<section class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Kop Surat -->
        <div class="flex items-center mb-6">
            <img src="/images/logo-bantul.png" alt="Logo Bantul" class="h-16 w-16 mr-4">
            <div>
                <div class="text-lg font-bold">PEMERINTAH KABUPATEN BANTUL</div>
                <div class="text-md font-semibold">DINAS KOMUNIKASI DAN INFORMATIKA (DISKOMINFO)</div>
                <div class="text-sm">Jl. Jenderal Sudirman No. 1, Bantul, Daerah Istimewa Yogyakarta 55711</div>
            </div>
        </div>

        <h1 class="text-2xl font-bold mb-6">Preview PDF Tabel Harga</h1>

        <div class="mb-4">
            <a href="{{ route('tabel-harga.download', request()->query()) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Download PDF
            </a>
        </div>

        <iframe src="{{ route('tabel-harga.preview-pdf', request()->query()) }}" 
        width="100%" height="600px" class="border rounded-lg"></iframe>

        <div class="mt-6 text-right text-sm text-gray-600">
            Dicetak : {{ now()->format('d M Y H:i') }}
        </div>
    </div>
</section>
@endsection
