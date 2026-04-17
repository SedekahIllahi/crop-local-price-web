@extends('layouts.admin.master')

@section('title', 'Menu')

@section('breadcrumb')
    {{ Breadcrumbs::render('navigations') }}
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
            {{-- START: Add New Data --}}
            @can('settings-navs.create')
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
        <div class="trezo-card-content" id="dataTable">
            <div class="table-responsive overflow-x-auto p-2">
                <table id="data-table" class="display stripe group" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Permission Identifier</th>
                            <th class="text-center">URL</th>
                            <th class="text-center">Order</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Display</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('settings-navs.read')
                            @foreach ($navigations as $nav)
                                <tr>
                                    <td class="text-left align-middle">
                                        <div class="flex items-center gap-2">
                                            <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 !text-[22px] leading-none relative -top-px">
                                                {{ $nav['icon'] }}
                                            </i>
                                            <span class="title leading-none text-left">
                                                {{ $nav['name'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-0 py-1 text-start">{{ $nav->slug }}</td>
                                    <td class="px-0 py-1 text-start">{{ $nav->url }}</td>
                                    <td class="px-2 py-1 text-start">
                                        {{ $nav->order }}
                                    </td>
                                    <td class="px-3 py-1 text-start">
                                        <small>
                                            <span class="px-[8px] py-[3px] inline-block bg-{{ $nav->active == 1 ? 'primary' : 'danger' }}-50 dark:bg-[#15203c] text-{{ $nav->active == 1 ? 'primary' : 'danger' }}-500 rounded-sm font-medium text-xs">{{ $nav->active == 1 ? 'Active' : 'Deactive' }}</span>
                                        </small>
                                    <td class="px-3 py-1 text-start">
                                        <small>
                                            <span class="px-[8px] py-[3px] inline-block bg-{{ $nav->display == 1 ? 'primary' : 'danger' }}-50 dark:bg-[#15203c] text-{{ $nav->display == 1 ? 'primary' : 'danger' }}-500 rounded-sm font-medium text-xs">{{ $nav->display == 1 ? 'Display' : 'Hidden' }}</span>
                                        </small>
                                    </td>
                                    <td class="px-4 py-1 text-center">
                                        <div class="flex items-center gap-[9px]">
                                            @can('settings-navs.update')
                                                <button type="button" class="btn-modal-edit-nav text-warning-500 dark:text-warning-400 leading-none custom-tooltip" id="customTooltip" data-text="Edit"
                                                    data-id="{{ $nav->id }}"
                                                    data-url-action="{{ route('settings.navs.update', $nav->id) }}"
                                                    data-url-get="{{ route('settings.navs.edit', $nav->id) }}">
                                                    <i class="material-symbols-outlined !text-md">
                                                        edit
                                                    </i>
                                                </button>
                                            @endcan
                                            @can('settings-navs.delete')
                                                <form action="{{ route('settings.navs.destroy', $nav->id) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" onclick="confirmDelete(this);" class="text-danger-500 leading-none custom-tooltip" id="customTooltip" data-text="Delete">
                                                        <i class="material-symbols-outlined !text-md">
                                                            delete
                                                        </i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @if ($nav->child->count() > 0)
                                    @foreach ($nav->child as $child)
                                        <tr>
                                            <td class="px-5 py-1 text-start">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-2">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            {{ $child->name ?? '-' }}
                                                        </div>
                                                    </div>
                                                </small>
                                            </td>
                                            <td class="px-1 py-1 text-start">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-2">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            {{ $child->slug ?? '-' }}
                                                        </div>
                                                    </div>
                                                </small>
                                            </td>
                                            <td class="px-1 py-1 text-start">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-2">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            {{ $child->url ?? '-' }}
                                                        </div>
                                                    </div>
                                                </small>
                                            </td>
                                            <td class="px-5 py-1 text-center">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-4">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            {{ $child->order ?? '-' }}
                                                        </div>
                                                    </div>    
                                                </small>
                                            </td>
                                            <td class="px-4 py-1 text-center {{ $child->active == 1 ? 'text-success' : 'text-danger' }}">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-4">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            <span class="px-[8px] py-[3px] inline-block bg-{{ $child->active == 1 ? 'primary' : 'danger' }}-50 dark:bg-[#15203c] text-{{ $child->active == 1 ? 'primary' : 'danger' }}-500 rounded-sm font-medium text-xs">{{ $child->active == 1 ? 'Active' : 'Deactive' }}</span>
                                                        </div>
                                                    </div>
                                                </small>
                                            </td>
                                            <td class="px-4 py-1 text-center {{ $child->display == 1 ? 'text-success' : 'text-danger' }}">
                                                <small>
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-4">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            <span class="px-[8px] py-[3px] inline-block bg-{{ $child->display == 1 ? 'primary' : 'danger' }}-50 dark:bg-[#15203c] text-{{ $child->display == 1 ? 'primary' : 'danger' }}-500 rounded-sm font-medium text-xs">{{ $child->display == 1 ? 'Display' : 'Hidden' }}</span>
                                                        </div>
                                                    </div>
                                                </small>
                                            </td>
                                            <td class="px-5 py-1 text-center">
                                                <div class="flex items-center gap-[9px]">
                                                    @can('settings-navs.update')
                                                        <button type="button" class="btn-modal-edit-nav text-warning-500 dark:text-warning-400 leading-none custom-tooltip" id="customTooltip" data-text="Edit"
                                                        data-id="{{ $child->id }}"
                                                        data-url-action="{{ route('settings.navs.update', $child->id) }}"
                                                        data-url-get="{{ route('settings.navs.edit', $child->id) }}">
                                                            <i class="material-symbols-outlined !text-md">
                                                                edit
                                                            </i>
                                                        </button>
                                                    @endcan
                                                    @can('settings-navs.delete')
                                                        <form action="{{ route('settings.navs.destroy', $child->id) }}" method="post"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" onclick="confirmDelete(this);" class="text-danger-500 leading-none custom-tooltip" id="customTooltip" data-text="Delete">
                                                                <i class="material-symbols-outlined !text-md">
                                                                    delete
                                                                </i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endcan
                    </tbody>
                </table>
            </div>
        </div>
        {{-- END: Data Table --}}
    </div>
    <!-- END: Data Table -->

    {{-- START: Import Modal Add --}}
    @can('settings-navs.create')
        @include('admin.settings.navigations.partials.modal-add')
    @endcan
    {{-- END: Import Modal Add --}}

    {{-- START: Import Modal Edit --}}
    @can('settings-navs.update')
        @include('admin.settings.navigations.partials.modal-edit')
    @endcan
    {{-- END: Import Modal Edit --}}
    
    {{-- START: Form Delete --}}
    @can('settings-navs.update')
        <form action="" id="form-delete" method="POST" id="form-delete">
            @csrf
            @method('DELETE')
        </form>
    @endcan
    {{-- END: Form Delete --}}
@endsection

@push('scripts')
    {{-- Start: Data Table --}}
    {{-- DataTables JS --}}
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.tailwindcss.js') }}"></script>
    <script>
        $('#data-table').DataTable({
            responsive: true,
            "ordering": false,
            "pageLength": 100
        });
    </script>
    {{-- End: Data Table --}}


    {{-- Start: Select 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Start: Select2 For Modal Add --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: '- Select This Choice -',
                allowClear: true
            });
        });
    </script>
    {{-- End: Select 2 For Modal Add --}}

    {{-- Start: Select2 for Modal Edit --}}
    <script>
        $(document).ready(function() {
            $('#roles').select2({
                width: '100%',
                placeholder: '- Select This Choice -',
                allowClear: true,
            });
        });
    </script>
    {{-- End: Select2 for Modal Edit --}}
    {{-- End: Select 2 --}}
@endpush


