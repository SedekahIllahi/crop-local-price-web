{{-- Trigger Modal Edit --}}
<button id="modal-edit-toggle" type="button"></button>

<div class="modal-edit z-[999] fixed transition-all inset-0 overflow-x-hidden overflow-y-auto" id="modal-edit">
    <!-- popup-dialog: centered and responsive widths (mobile -> large) -->
    <div class="popup-dialog flex transition-all items-center justify-center min-h-screen px-4 sm:px-6">
        <div class="trezo-card w-full max-w-[95%] sm:max-w-[720px] md:max-w-[900px] lg:max-w-[1100px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                <div class="trezo-card-title">
                    <h5 class="mb-0" id="modal-title">
                        Edit Data @yield('title')
                    </h5>
                </div>
                <div class="trezo-card-subtitle">
                    <button type="button" class="text-[23px] transition-all leading-none text-black dark:text-white hover:text-primary-500" id="modal-edit-toggle">
                        <i class="ri-close-fill"></i>
                    </button>
                </div>
            </div>
            {{-- START: Form Edit --}}
            <form action="" method="POST" id="form-edit">
                @csrf
                @method('PUT')
                {{-- START: Modal Body --}}
                <div class="trezo-card-content pb-[20px] md:pb-[25px]">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-[20px] md:gap-[25px]">
                        {{-- START: Name --}}
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Name
                                <strong class="text-red-500">*</strong>
                            </label>
                            <input type="text" name="name" id="name" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Insert user full name here ..." required>
                        </div>
                        {{-- END: Name --}}

                        {{-- START: Email --}}
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Email
                                <strong class="text-red-500">*</strong>
                            </label>
                            <input type="email" name="email" id="email" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Insert user email here ..." required>
                        </div>
                        {{-- END: Email --}}
                        
                        {{-- START: Role --}}
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Role
                                <strong class="text-red-500">*</strong>
                            </label>
                            <select name="roles[]" id="roles" class="h-[45px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500" multiple="multiple" required>
                                <option selected>- Select Role -</option>
                                @foreach ($roles as $role)
                                    @if ($role->name !== \App\Enums\RoleEnum::DEVELOPER->value)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        {{-- END: Role --}}

                        {{-- START: Is Active --}}
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Account Status
                                <strong class="text-red-500">*</strong>
                            </label>
                            <select name="is_active" id="is_active" class="h-[45px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        {{-- END: Is Active --}}
                    </div>

                    {{-- START: Password --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mt-5">
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Password
                            </label>
                            <input type="password" name="password" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Insert user password here ...">
                        </div>
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Password Confirmation
                            </label>
                            <input type="password" name="password_confirmation" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Please retype user password for confirmation here ...">
                        </div>
                    </div>
                    {{-- END: Password --}}
                </div>
                {{-- END: Modal Body --}}
                {{-- START: Modal Footer --}}
                <div class="trezo-card-footer flex items-center justify-between -mx-[20px] md:-mx-[25px] px-[20px] md:px-[25px] pt-[20px] md:pt-[25px] border-t border-gray-100 dark:border-[#172036]">
                    <button class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400" type="button" id="modal-add-toggle">
                        Close
                    </button>
                    <button class="inline-block py-[10px] px-[30px] bg-primary-500 text-white transition-all hover:bg-primary-400 rounded-md border border-primary-500 hover:border-primary-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]" 
                        type="submit">
                        Save Changes
                    </button>
                </div>
                {{-- END: Modal Footer --}}
            </form>
            {{-- END: Form Edit --}}
        </div>
    </div>
</div>

@push('scripts')
    {{-- START: Showing Modal Edit --}}
    <script>
        // Add New Popup Toggle
        const editNewPopupID = document.getElementById("modal-edit");
        if (editNewPopupID) {
            var buttons = document.querySelectorAll("#modal-edit-toggle");
            buttons.forEach(function(button) {
                button.addEventListener("click", function() {
                    // Toggle class on the div
                    var divElement = document.getElementById("modal-edit");
                    divElement.classList.toggle("active");
                });
            });
        }
    </script>
    {{-- END: Showing Modal Edit --}}

    <!-- Start Edit User (Modal) -->
    <script>
        $(document).on('click', '.btn-modal-edit-user', function(e) {
            e.preventDefault();

            // Trigger button to open modal
            $('#modal-edit-toggle').click();

            // User Id
            var userId = $(this).data('id');
            var urlFormAction = $(this).data('url-action');
            var urlGetData = $(this).data('url-get');
            // Send request to get user data
            $.ajax({
                url: urlGetData, // Url for get data edit
                type: 'GET',
                success: function(response) {
                    // Modal title
                    $('#modal-title').text('Edit Data Pengguna | ' + response.name);
                    // Set form action
                    $('#form-edit').attr('action', urlFormAction);
                    // Set value to form inputs
                    $('#form-edit').find('#name').val(response.name);
                    $('#form-edit').find('#email').val(response.email);
                    $('#form-edit').find('#roles').val(response.role_names).trigger('change');
                    $('#form-edit').find('#is_active').val(response.is_active).trigger('change');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data.',
                    });
                }
            });
        });        
        </script>
        <!-- End Edit User (Modal) -->
@endpush