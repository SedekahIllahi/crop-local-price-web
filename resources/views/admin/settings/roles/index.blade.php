@extends('layouts.admin.master')

@section('title', 'Peran')

@section('breadcrumb')
    {{ Breadcrumbs::render('roles') }}
@endsection

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/datatables-2.3.4/datatables.tailwindcss.css') }}">
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
            {{-- START: Add New Data --}}
            @can('settings-roles.create')
                <div class="trezo-card-subtitle sm:flex sm:items-center">
                    <div class="trezo-card-dropdown relative">
                        <button class="trezo-card-dropdown-btn py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] bg-primary-500 text-white transition-all hover:bg-primary-400 rounded-md border border-primary-500 hover:border-primary-400"
                            type="button" id="modal-add-toggle">
                            <i class="ri-menu-add-line"></i>
                            Add New @yield('title')
                        </button>
                    </div>
                </div>
            @endcan
            {{-- END: Add New Data --}}
        </div>
        {{-- START: Data Table --}}
        <div class="trezo-card-content" id="">
            <div class="table-responsive overflow-x-auto p-2">
                <table id="data-table" class="display stripe group" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="ltr:!text-left rtl:!text-right">Name</th>
                            <th class="ltr:!text-left rtl:!text-right">Permissions</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('settings-roles.read')    
                            @forelse ($roles as $role)
                                <tr class="">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ $role->name ?? '-' }}</td>
                                    <td class="text-left">
                                        @can('settings-roles.read')
                                            <a href="{{ route('settings.roles.show', $role->id) }}"
                                                class="flex items-center justify-center text-primary-500 leading-none custom-tooltip">
                                                <i class="material-symbols-outlined !text-md">
                                                    visibility
                                                </i>
                                            </a>
                                        @endcan
                                    </td>
                                    <td class="text-center">
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @can('settings-roles.update')
                                                <button type="button" id="btn-modal-edit-role"
                                                    data-id={{ $role->id }}
                                                    data-url-action="{{ route('settings.roles.update', $role->id) }}"
                                                    data-url-get="{{ route('settings.roles.edit', $role->id) }}"
                                                    class="btn-modal-edit-role flex items-center justify-center text-warning-500 dark:text-warning-400 leading-none custom-tooltip">
                                                    <i class="material-symbols-outlined !text-md">
                                                        edit
                                                    </i>
                                                </button>
                                            @endcan
                                            @can('settings-roles.delete')
                                                <form action="{{ route('settings.roles.destroy', $role->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center justify-center text-danger-500 leading-none custom-tooltip"
                                                        onclick="confirmDelete(this);">
                                                        <i class="material-symbols-outlined !text-md">
                                                            delete
                                                        </i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada menu ditemukan</td>
                                </tr>
                            @endforelse
                        @endcan
                    </tbody>
                </table>
            </div>
        </div>
        {{-- END: Data Table --}}
    </div>
    <!-- END: Data Table -->

    {{-- START: Import Modal Add --}}
    @can('settings-roles.create')
        @include('admin.settings.roles.partials.modal-add')
    @endcan
    {{-- END: Import Modal Add --}}

    {{-- START: Import Modal Edit --}}
    @can('settings-roles.delete')
        @include('admin.settings.roles.partials.modal-edit')
    @endcan
    {{-- END: Import Modal Edit --}}

    {{-- START: Form Delete --}}
    @can('settings-roles.delete')
        <form action="" id="form-delete" method="POST" id="form-delete">
            @csrf
            @method('DELETE')
        </form>
    @endcan
    {{-- END: Form Delete --}}
@endsection

@push('scripts')
    {{-- DataTables JS --}}
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.tailwindcss.js') }}"></script>

    {{-- Start: Implement datatable --}}
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                "columnDefs": [
                    { "targets": [2], "className": "text-center" }
                ],
                columns: [
                    { width: "5%" },
                    { width: "75%" },
                    { width: "5%" },
                    { width: "15%" }
                ],
                autoWidth: false
            });
        });
    </script>
    {{-- End: Implement datatable --}}
@endpush

