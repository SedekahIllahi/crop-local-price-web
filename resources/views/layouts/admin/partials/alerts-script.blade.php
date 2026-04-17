<script src="{{ URL::asset('assets/admin/js/sweetalert2.min.js') }}"></script>

{{-- Alert Action Notification --}}
<script>
    // Show error by type
    @foreach (['success', 'error', 'warning', 'info'] as $type)
        @if ($message = Session::get($type))
            @php
                $title = match ($type) {
                    'success' => 'Berhasil',
                    'error' => 'Gagal',
                    'warning' => 'Peringatan',
                    'info' => 'Informasi',
                    default => null,
                };
            @endphp
            Swal.fire({
                title: '{{ $title }}',
                text: '{{ $message }}',
                icon: '{{ $type }}',
                timer: 3000,
                confirmButtonText: 'OK'
            })
        @endif
    @endforeach

    // When has error more than 1
    @if ($errors->any())
        @foreach ($errors->all() as $message)
            Swal.fire({
                title: 'GAGAL',
                text: ' @json($message)',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        @endforeach
    @endif
</script>

{{-- Alert Action Disabled Button Delete --}}
<script>
    // Show error by type
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'btn-disabled-delete') {
            e.preventDefault();
            Swal.fire({
                title: 'Tidak dapat menghapus data',
                text: "Data telah digunakan pada data lain!",
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

{{-- Alert Action Confirmation --}}
<script>
    function confirmDelete(button) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#919191',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form terdekat dan submit
                button.closest('form').submit();
            }
        });
    }
</script>

{{-- Start action delete data --}}
<script>
    $(document).on('click', '#btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var urlFormAction = $(this).data('url-action');
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data tidak bisa dikembalikan setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lanjutkan ke form delete
                const form = $('#form-delete');
                form.attr('action', urlFormAction);
                form.submit();
            }
        })
    });
</script>
{{-- End action delete data --}}
