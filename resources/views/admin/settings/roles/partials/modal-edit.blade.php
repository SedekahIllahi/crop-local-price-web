<!-- Modal Edit -->

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

{{-- Trigger Modal Edit --}}
<button id="modal-edit-toggle" type="button"></button>

{{-- START: Modal --}}
<div class="modal-edit z-[999] fixed transition-all inset-0 overflow-x-hidden overflow-y-auto" id="modal-edit">
    <!-- popup-dialog: centered and responsive widths (mobile -> large) -->
    <div class="popup-dialog flex transition-all items-center justify-center min-h-screen px-4 sm:px-6">
        <div class="trezo-card w-full max-w-[95%] sm:max-w-[720px] md:max-w-[900px] lg:max-w-[1100px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            
            {{-- START: Modal Header --}}
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
            {{-- END: Modal Header --}}

            {{-- START: Form Edit --}}
            <form id="form-edit" method="POST">
                @csrf
                @method('put')
                {{-- START: Modal Body --}}
                <div class="trezo-card-content pb-[20px] md:pb-[25px]">
                    {{-- START: Name --}}
                    <div>
                        <label class="mb-[12px] font-medium block">
                            Name
                            <strong class="text-red-500">*</strong>
                        </label>
                        <input type="text" name="name" id="name" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Insert role name here ..." required>
                    </div>
                    {{-- END: Name --}}
                </div>
                {{-- END: Modal Body --}}
                {{-- START: Modal Footer --}}
                <div class="trezo-card-footer flex items-center justify-between -mx-[20px] md:-mx-[25px] px-[20px] md:px-[25px] pt-[20px] md:pt-[25px] border-t border-gray-100 dark:border-[#172036]">
                    <button class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400" type="button" id="modal-edit-toggle">
                        Close
                    </button>
                    <button type="submit" class="inline-block py-[10px] px-[30px] bg-primary-500 text-white transition-all hover:bg-primary-400 rounded-md border border-primary-500 hover:border-primary-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]">
                        Save Changes
                    </button>
                </div>
                {{-- END: Modal Footer --}}
            </form>
            {{-- END: Form Edit --}}

        </div>
    </div>
</div>
{{-- END: Modal --}}

@push('scripts')
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
    
    <!-- Start Edit User (Modal) -->
    <script>
        $(document).on('click', '.btn-modal-edit-role', function(e) {
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
                    $('#modal-title').text('Edit Data Peran | ' + response.name);
                    // Set form action
                    $('#form-edit').attr('action', urlFormAction);
                    // Set value to form inputs
                    $('#form-edit').find('#name').val(response.name);
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
