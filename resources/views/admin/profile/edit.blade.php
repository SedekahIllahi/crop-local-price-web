@extends('layouts.admin.master')

@section('title', 'Profile Saya')

@section('breadcrumb')
    {{ Breadcrumbs::render('profile') }}
@endsection

@section('content')
{{-- START : Update Biodata --}}
<div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
    <div class="trezo-card-content">
        <div class="flex flex-col items-center mb-[25px]">
            <div class="flex flex-col items-center">
                @php
                    $profile_photo = Auth::user()?->userProfile?->profile_photo
                    ? URL::asset('storage/' . Auth::user()->userProfile->profile_photo)
                    : URL::asset('assets/admin/images/users/default.jpg');
                @endphp
                <img src="{{ $profile_photo }}" alt="user-image" class="rounded-full w-[75px] mb-2">
                <p class="mb-[20px] md:mb-[25px] text-center">
                    {{ ucwords(implode(', ', $user->roles->pluck('name')->toArray())) }}
                </p>
            </div>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" id="generalInformationForm" enctype="multipart/form-data">
            @csrf
            @method('patch')
            {{-- START: Name & Email --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        Name
                    </label>
                    <input type="text" name="name" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" value="{{ $user->name }}" required>
                </div>
                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        Email
                    </label>
                    <input type="email" name="email" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" value="{{ $user->email }}" readonly>
                </div>
            </div>
            {{-- END: Name & Email --}}

            {{-- START: Profile Photo --}}
            <label class="mb-[10px] text-black dark:text-white font-medium block mt-[20px] md:mt-[25px]">
                Foto Profile
            </label>
            
            <div id="fileUploader">
                <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[88px] px-[20px] border border-gray-200 dark:border-[#172036]">
                    <div class="flex items-center justify-center">
                        <div class="w-[35px] h-[35px] border border-gray-100 dark:border-[#15203c] flex items-center justify-center rounded-md text-primary-500 text-lg ltr:mr-[12px] rtl:ml-[12px]">
                            <i class="ri-upload-2-line"></i>
                        </div>
                        <p class="leading-[1.5]">
                            <strong class="text-black dark:text-white">Click to upload</strong><br> you file here
                        </p>
                    </div>
                    <input type="file" name="profile_photo" id="fileInput" class="absolute top-0 left-0 right-0 bottom-0 rounded-md z-[1] opacity-0 cursor-pointer" 
                    accept="image/*">
                </div>
                <ul id="fileList"></ul>
            </div>
            {{-- END: Profile Photo --}}

            {{-- START: Btn Save Bio Data --}}
            <div class="mt-[20px] md:mt-[25px]">
                <button type="submit" class="font-medium inline-block transition-all rounded-md md:text-md py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                    <span class="inline-block relative ltr:pl-[29px] rtl:pr-[29px]">
                        <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2">
                            check
                        </i>
                        Update Profile
                    </span>
                </button>
            </div>
            {{-- END: Btn Save Bio Data --}}
        </form>
    </div>
</div>
{{-- END : Update Biodata --}}

{{-- START : Update Password --}}
<div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
    <div class="trezo-card-content">
        <form action="{{ route('password.update') }}" id="updatePasswordForm" method="POST">
            @csrf
            @method('put')
            {{-- START: Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        Current Password
                    </label>
                    <input type="password" name="current_password" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                    placeholder="Type current password ..." required>
                </div>

                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        New Password
                    </label>
                    <input type="password" name="password" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" 
                    placeholder="Type new password ..." required>
                </div>

                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" 
                    placeholder="Confirm new password ..." required>
                </div>
            </div>
            {{-- END: Password --}}

            {{-- START: Btn Save Bio Data --}}
            <div class="mt-[20px] md:mt-[25px]">
                <button type="submit" class="font-medium inline-block transition-all rounded-md md:text-md py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-red-500 text-white hover:bg-red-400">
                    <span class="inline-block relative ltr:pl-[29px] rtl:pr-[29px]">
                        <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2">
                            key
                        </i>
                        Update Password
                    </span>
                </button>
            </div>
            {{-- END: Btn Save Bio Data --}}
        </form>
    </div>
</div>
{{-- END : Update Password --}}

{{-- START: Delete Account --}}
<div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
    <div class="trezo-card-content">
        <form action="{{ route('profile.destroy') }}" id="deleteAccountForm" method="POST">
            @csrf
            @method('delete')
            {{-- START: Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-[20px] md:gap-[25px]">
                <div>
                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                        Current Password for Confirmation
                    </label>
                    <input type="password" name="password" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                    placeholder="Type current password for confirmation if you need delete account ..." required>
                </div>
            </div>
            {{-- END: Password --}}

            {{-- START: Btn Save Bio Data --}}
            <div class="mt-[20px] md:mt-[25px]">
                <button type="submit" id="delete-account-btn" class="font-medium inline-block transition-all rounded-md md:text-md py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-red-500 text-white hover:bg-red-400">
                    <span class="inline-block relative ltr:pl-[29px] rtl:pr-[29px]">
                        <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2">
                            delete
                        </i>
                        Delete Account
                    </span>
                </button>
            </div>
            {{-- END: Btn Save Bio Data --}}
        </form>
    </div>
</div>
{{-- END: Delete Account --}}
@endsection

@push('scripts')
    <script>
        function submitFormGeneralInformation() {
            let form = document.getElementById('generalInformationForm');

            // Cek validitas form sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan error bawaan HTML
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda ingin memperbarui informasi profile anda?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika valid, kirim form
                }
            });
        }

        function submitFormUpdatePassword() {
            let form = document.getElementById('updatePasswordForm');

            // Cek validitas form sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan error bawaan HTML
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda ingin memperbarui kata sandi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika valid, kirim form
                }
            });
        }

        // Confirmation before delete account
        document.getElementById('delete-account-btn').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah submit form langsung

            let form = document.getElementById('deleteAccountForm');

            // Cek validitas form sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan error bawaan HTML
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus akun saya!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika valid, kirim form
                }
            });
        });
    </script>
@endpush



