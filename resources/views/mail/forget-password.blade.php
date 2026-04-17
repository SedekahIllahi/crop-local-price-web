<x-mail-layout>

    <div class="header text-center">
        <img class="img-fluid" src="{{ asset('assets/admin/images/authentication/img-auth-reset-password.png') }}" alt="Logo">
    </div>
    <div class="content">
        <h2 class="mb-4">Reset Password Anda</h2>
        <p class="text-muted mb-4">Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk mereset password Anda:</p>
        <div class="text-center mb-4">
            <a class="btn btn-reset btn-lg text-white" href="{{ $url }}">Reset Password</a>
        </div>
        <p class="text-muted small">Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dengan aman.</p>
        <p class="text-muted small mb-0">Tautan reset password ini akan kedaluwarsa dalam {{ config('auth.passwords.users.expire') / 60 }} jam.</p>
    </div>

</x-mail-layout>
