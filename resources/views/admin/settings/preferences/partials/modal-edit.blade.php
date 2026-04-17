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
            <form id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <input type="hidden" name="is_asset" id="is_asset">
                {{-- START: Modal Body --}}
                <div class="trezo-card-content pb-[20px] md:pb-[25px]">
                    {{-- START: Asset --}}
                    <div class="row" id="content-asset">
                        {{-- START: Preview If is_asset = true --}}
                        <div class="flex flex-col items-center mb-[25px]" id="content-asset-preview">
                            <div class="flex flex-col items-center">
                                <img src="" alt="user-image" class="rounded-full w-[300px] mb-2" id="content-asset-preview--image">
                                <p class="mb-[20px] md:mb-[25px] text-center" id="content-asset-preview--text"></p>
                            </div>
                        </div>
                        {{-- END: Preview If is_asset = true --}}
    
                        {{-- START: Asset Upload (if this is_asset = ture) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]" id="content-asset-upload">
                            <div class="">
                                <label class="mb-[12px] font-medium block">
                                    Path
                                </label>
                                <input type="text" name="path" id="path" class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] p-[10px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" readonly />
                            </div>
                            <div class="">
                                <label class="mb-[12px] font-medium block">
                                    File Name
                                </label>
                                <input type="text" name="file_name" id="file_name" class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] p-[10px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" readonly />
                            </div>
                            <div id="">
                                <label class="mb-[12px] font-medium block">
                                    Upload File
                                </label>
                                <input type="file" name="file_asset" id="file_asset" class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] p-[10px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" />
                            </div>
                        </div>
                        {{-- END: Asset Upload (if this is_asset = ture) --}}
                    </div>
                    {{-- END: Asset --}}

                    {{-- START: Value --}}
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-[20px] md:gap-[25px] mt-5" id="content-form-value">
                        <div>
                            <label class="mb-[12px] font-medium block">
                                Value
                                <strong class="text-red-500">*</strong>
                            </label>
                            <textarea type="text" name="value" id="value" class="h-[140px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] p-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="Fill preference value ..." required></textarea>
                        </div>
                    </div>
                    {{-- END: Value --}}
                </div>
                {{-- END: Modal Body --}}

                {{-- START: Modal Footer --}}
                <div class="trezo-card-footer flex items-center justify-between -mx-[20px] md:-mx-[25px] px-[20px] md:px-[25px] pt-[20px] md:pt-[25px] border-t border-gray-100 dark:border-[#172036]">
                    <button class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400" type="button" id="modal-edit-toggle">
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

    {{-- Start Select 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    {{-- End Select 2 --}}

    <!-- Start Edit Preference (Modal) -->
    <script>
        $(document).on('click', '.btn-modal-edit-pref', function(e) {
            e.preventDefault();

            // Trigger button to open modal
            $('#modal-edit-toggle').click();

            // pref Id
            var prefId = $(this).data('id');
            var urlFormAction = $(this).data('url-action');
            var urlGetData = $(this).data('url-get');
            // Send request to get user data
            $.ajax({
                url: urlGetData, // Url for get data edit
                type: 'GET',
                success: function(response) {
                    // Modal title
                    $('#modal-title').text('Edit Data Preference - ' + response.name);
                    // Set form action
                    $('#form-edit').attr('action', urlFormAction);
                    // Set value to form inputs
                    $('#form-edit').find('#value').val(response.value);

                    // Hidden asset content if not is_asset
                    if (response.is_asset) {
                        // Show asset content
                        $('#content-asset').show();
                        // Hide value content
                        $('#content-form-value').hide();
                        // Set form input file_asset to required true
                        $('#form-edit').find('#file_asset').attr('required', true);
                        // Set is_asset
                        $('#form-edit').find('#is_asset').val('1');

                        /* Set path */
                        var filepath = response.value;
                        var filename = filepath.substring(filepath.lastIndexOf('/') + 1);
                        var directory = filepath.substring(0, filepath.lastIndexOf('/'));
                        const baseUrl = window.location.origin;
                        const imgSrc = baseUrl + '/' + filepath;
                        // Set path
                        $('#form-edit').find('#path').val(directory + '/');
                        // Set file_name
                        $('#form-edit').find('#file_name').val(filename);
                        // Set preview image and text
                        $('#content-asset-preview--image').attr('src', imgSrc);
                        $('#content-asset-preview--text').text(filename);
                    } else {
                        // Hide asset content
                        $('#content-asset').hide();
                        // Show value content
                        $('#content-form-value').show();
                        // Set is_asset
                        $('#form-edit').find('#is_asset').val('0');
                    }
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