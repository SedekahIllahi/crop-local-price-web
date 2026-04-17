@extends('layouts.admin.master')

@section('title', 'Hak Akses Peran')

@section('breadcrumb')
    {{ Breadcrumbs::render('roles-permissions', $role->id, $role->name) }}
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
            @can('settings-users.create')
                <div class="trezo-card-subtitle sm:flex sm:items-center">
                    <div class="trezo-card-dropdown relative">
                        <button data-url="{{ route('settings.roles.index') }}" class="trezo-card-dropdown-btn py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] bg-gray-500 text-white transition-all hover:bg-gray-400 rounded-md border border-gray-500 hover:border-gray-400" 
                            type="button" onclick="window.location.href=this.getAttribute('data-url')">
                            <i class="ri-arrow-go-back-line"></i>
                            Back
                        </button>

                        <button class="trezo-card-dropdown-btn py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400" type="submit" onclick="submitForm()">
                            <i class="ri-save-line"></i>
                            Save @yield('title')
                        </button>
                    </div>
                </div>
            @endcan
            {{-- END: Add New Data --}}
        </div>
        {{-- START: Data Table --}}
        <div class="trezo-card-content" id="dataTable">
            <div class="table-responsive overflow-x-auto p-2">
                {{-- Start: Form Update Permissions --}}
                <form action="{{ route('settings.roles.permissions', $role->id) }}" id="permissions-form" method="POST">
                    @csrf
                    @method('PUT')
                    <table id="data-table" class="display stripe group" style="width:100%">
                        <thead>
                            <th class="text-center">Menu</th>
                            <th class="text-center">Read</th>
                            <th class="text-center">Create</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                            @can('settings-roles.read')    
                                @forelse ($navigations as $nav)
                                    {{-- Start single & parent navs --}}
                                    <tr class="">
                                        {{-- Start Nav Icon & Name --}}
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
                                        {{-- End Nav Icon & Name --}}

                                        {{-- Start Permissions (Read) --}}
                                        <td class="text-center">
                                            <input id="checkbox_{{ $nav->id }}_read" name="permissions[]"
                                            type="checkbox" value="{{ strtolower($nav->slug) . '.read' }}"
                                            class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                            {{ in_array(strtolower($nav->slug) . '.read', $permissions) ? 'checked' : '' }}>
                                        </td>
                                        {{-- End Permissions (Read) --}}
                                        
                                        {{-- Start Permissions (Create) --}}
                                        <td class="text-center">
                                            <input id="checkbox_{{ $nav->id }}_create" name="permissions[]"
                                            type="checkbox" value="{{ strtolower($nav->slug) . '.create' }}"
                                            class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                            {{ in_array(strtolower($nav->slug) . '.create', $permissions) ? 'checked' : '' }}>
                                        </td>
                                        {{-- End Permissions (Create) --}}

                                        {{-- Start Permissions (Update) --}}
                                        <td class="text-center">
                                            <input id="checkbox_{{ $nav->id }}_update" name="permissions[]"
                                            type="checkbox" value="{{ strtolower($nav->slug) . '.update' }}"
                                            class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                            {{ in_array(strtolower($nav->slug) . '.update', $permissions) ? 'checked' : '' }}>
                                        </td>
                                        {{-- End Permissions (Update) --}}

                                        {{-- Start Permissions (Delete) --}}
                                        <td class="text-center">
                                            <input id="checkbox_{{ $nav->id }}_delete" name="permissions[]"
                                            type="checkbox" value="{{ strtolower($nav->slug) . '.delete' }}"
                                            class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                            {{ in_array(strtolower($nav->slug) . '.delete', $permissions) ? 'checked' : '' }}>
                                        </td>
                                        {{-- End Permissions (Delete) --}}
                                    </tr>
                                    {{-- End single & parent navs --}}
                                    
                                    {{-- Start child navs --}}
                                    @if ($nav->child->count() > 0)
                                        @foreach ($nav->child as $child)
                                            <tr class="">
                                                {{-- Start Child Nav Icon & Name --}}
                                                <td class="text-left align-middle pl-8">
                                                    <div class="flex items-center gap-2 mr-4">
                                                        <div class="col-span">
                                                            <i class="material-symbols-outlined h-4 text-xs ml-4">
                                                                line_end_circle
                                                            </i>
                                                        </div> 
                                                        <div class="title leading-none text-left">
                                                            {{ $child->name ?? '-' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                {{-- End Child Nav Icon & Name --}}

                                                {{-- Start Permissions (Read) --}}
                                                <td class="text-center">
                                                    <input id="checkbox_{{ $child->id }}_read" name="permissions[]"
                                                    type="checkbox" value="{{ strtolower($child->slug) . '.read' }}"
                                                    class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                                    {{ in_array(strtolower($child->slug) . '.read', $permissions) ? 'checked' : '' }}>
                                                </td>
                                                {{-- End Permissions (Read) --}}
                                                
                                                {{-- Start Permissions (Create) --}}
                                                <td class="text-center">
                                                    <input id="checkbox_{{ $child->id }}_create" name="permissions[]"
                                                    type="checkbox" value="{{ strtolower($child->slug) . '.create' }}"
                                                    class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                                    {{ in_array(strtolower($child->slug) . '.create', $permissions) ? 'checked' : '' }}>
                                                </td>
                                                {{-- End Permissions (Create) --}}

                                                {{-- Start Permissions (Update) --}}
                                                <td class="text-center">
                                                    <input id="checkbox_{{ $child->id }}_update" name="permissions[]"
                                                    type="checkbox" value="{{ strtolower($child->slug) . '.update' }}"
                                                    class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                                    {{ in_array(strtolower($child->slug) . '.update', $permissions) ? 'checked' : '' }}>
                                                </td>
                                                {{-- End Permissions (Update) --}}

                                                {{-- Start Permissions (Delete) --}}
                                                <td class="text-center">
                                                    <input id="checkbox_{{ $child->id }}_delete" name="permissions[]"
                                                    type="checkbox" value="{{ strtolower($child->slug) . '.delete' }}"
                                                    class="size-4 border rounded-full appearance-none cursor-pointer bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-green-500 checked:border-green-500 dark:checked:bg-green-500 dark:checked:border-green-500 checked:disabled:bg-green-400 checked:disabled:border-green-400"
                                                    {{ in_array(strtolower($child->slug) . '.delete', $permissions) ? 'checked' : '' }}>
                                                </td>
                                                {{-- End Permissions (Delete) --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    {{-- End child navs --}}
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada menu ditemukan</td>
                                    </tr>
                                @endforelse
                            @endcan
                        </tbody>
                    </table>
                </form>
                {{-- End: Form Update Permissions --}}
            </div>
        </div>
        {{-- END: Data Table --}}
    </div>
    <!-- END: Data Table -->
@endsection

@push('scripts')
    {{-- DataTables JS --}}
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/datatables-2.3.4/dataTables.tailwindcss.js') }}"></script>

    {{-- Implement datatable --}}
    <script>
        /* Data Table */
        $(document).ready(function() {
            $('#data-table').DataTable({
                columns: [
                    { width: "60%" },
                    { width: "10%" },
                    { width: "10%" },
                    { width: "10%" },
                    { width: "10%" }
                ],
                autoWidth: false,
                pageLength: 100,
                ordering: false
            });
        });

        /* Submit form */
        function submitForm() {
            document.getElementById('permissions-form').submit();
        }

        // Back button
    </script>
@endpush



