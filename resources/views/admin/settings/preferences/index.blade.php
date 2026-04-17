@extends('layouts.admin.master')

@section('title', 'Preferensi')

@section('breadcrumb')
    {{ Breadcrumbs::render('preferences') }}
@endsection

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/datatables-2.3.4/datatables.tailwindcss.css') }}">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <!-- START: Data Table -->
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0">
                    Daftar @yield('title')
                </h5>
            </div>
        </div>
        {{-- START: Data Table --}}
        <div class="trezo-card-content" id="dataTable">
            <div class="table-responsive overflow-x-auto p-2">
                <table id="data-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Key</th>
                            <th class="text-center">Group</th>
                            <th class="text-center">Value</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($preferences as $preference)
                            <tr>
                                <td>{{ $preference->name ?? '-' }}</td>
                                <td>{{ $preference->group ?? '-' }}</td>
                                <td>{{ $preference->value ?? '-' }}</td>
                                <td>
                                    @can('settings-preferences.update')
                                        {{-- BUTTON MINI FOR IN TABLE --}}
                                        <div class="flex items-center gap-[9px]">
                                            {{-- Button Edit --}}
                                            <button type="button" class="btn-modal-edit-pref text-warning-500 dark:text-warning-400 leading-none custom-tooltip" id="customTooltip" data-text="Edit"
                                                data-id="{{ $preference->id }}"
                                                data-url-action="{{ route('settings.preferences.update', $preference->id) }}"
                                                data-url-get="{{ route('settings.preferences.edit', $preference->id) }}">
                                                <i class="material-symbols-outlined !text-md">
                                                    edit
                                                </i>
                                            </button>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Not found preferences</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- END: Data Table --}}
    </div>
    <!-- END: Data Table -->

    {{-- START: Import Modal Edit --}}
    @can('settings-preferences.update')
        @include('admin.settings.preferences.partials.modal-edit')
    @endcan
    {{-- END: Import Modal Edit --}}

    {{-- START: Form Delete --}}
    <form action="" id="form-delete" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
    </form>
    {{-- END: Form Delete --}}
@endsection

@push('scripts')
    {{-- Start: Data Table --}}
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.tailwindcss.js') }}"></script>

    
    <script>
        $('#data-table').DataTable({
            responsive: true,
            // "ordering": false,
            "pageLength": 100
        });
    </script>
    {{-- End: Data Table --}}

    {{-- Start: Select 2 --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{-- End: Select 2 --}}
@endpush
