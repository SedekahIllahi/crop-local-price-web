<x-mail-layout>

    <div class="header text-center">
        <img class="img-fluid" src="{{ asset( 'storage/lte/assets/admin/img/AdminLTEFullLogo.png') }}" alt="Logo">
    </div>
    <div class="content">
        <h2 class="mb-4 text-center">Verifikasi pendaftaran akun anda</h2>
        <p class="text-muted mb-4">Terima kasih <b>{{ $user->name }}</b>, telah mendaftar pada platform kami! Silakan verifikasi alamat email Anda untuk mendapatkan akses ke semua fitur.</p>
        <div class="text-center mb-4">
            <a class="btn btn-verify btn-lg rounded text-white text-center rounded" href="{{ $url }}"><i class="bi bi-patch-check-fill"></i> Verifikasi Pendaftaran Anda</a>
        </div>
        <p class="text-muted small">Jika Anda tidak merasa membuat akun, Anda dapat mengabaikan email ini dengan aman.</p>
        <p class="text-muted small mb-0">Tautan verifikasi ini akan kedaluwarsa dalam {{ config('auth.verification.expire') / 60 }} jam.</p>
    </div>

</x-mail-layout>
