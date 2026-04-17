@extends('layouts.admin.master')

@section('title', 'Stok Bapok')

@section('breadcrumb')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-500 dark:text-gray-400 dark:hover:text-white">
                    <i class="material-symbols-outlined text-base mr-1">home</i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="material-symbols-outlined text-gray-400 rtl:rotate-180">chevron_right</i>
                    <span class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400">Pasar</span>
                </div>
            </li>
            @if(isset($pasar))
            <li>
                <div class="flex items-center">
                    <i class="material-symbols-outlined text-gray-400 rtl:rotate-180">chevron_right</i>
                    <span class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $pasar->nama_pasar }}</span>
                </div>
            </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="material-symbols-outlined text-gray-400 rtl:rotate-180">chevron_right</i>
                    <span class="ms-1 text-sm font-medium text-primary-500 dark:text-primary-400">Stok Bapok</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/datatables-2.3.4/datatables.tailwindcss.css') }}">
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-sukses {
        background-color: #dcfce7;
        color: #166534;
    }
    .status-gagal {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .status-pending {
        background-color: #fef9c3;
        color: #854d0e;
    }
    .status-draft {
        background-color: #e0e7ff;
        color: #3730a3;
    }
    .dark .status-sukses {
        background-color: rgba(22, 163, 74, 0.2);
        color: #86efac;
    }
    .dark .status-gagal {
        background-color: rgba(220, 38, 38, 0.2);
        color: #fca5a5;
    }
    .dark .status-pending {
        background-color: rgba(234, 179, 8, 0.2);
        color: #fde68a;
    }
    .dark .status-draft {
        background-color: rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .action-btn-view {
        background-color: #dbeafe;
        color: #1d4ed8;
    }
    .action-btn-view:hover {
        background-color: #2563eb;
        color: white;
    }
    .action-btn-edit {
        background-color: #dcfce7;
        color: #166534;
    }
    .action-btn-edit:hover {
        background-color: #16a34a;
        color: white;
    }
    .dark .action-btn-view {
        background-color: rgba(37, 99, 235, 0.2);
    }
    .dark .action-btn-edit {
        background-color: rgba(22, 163, 74, 0.2);
    }
    .page-header {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        color: white;
    }
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
    }
    .dark .filter-section {
        background: #0c1427;
        border-color: #1e293b;
    }
    /* Dropdown Action Menu */
    .action-dropdown {
        position: relative;
        display: inline-block;
    }
    .action-dropdown-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: #f3f4f6;
        color: #6b7280;
        transition: all 0.2s ease;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .action-dropdown-btn:hover {
        background-color: #e5e7eb;
        color: #374151;
    }
    .dark .action-dropdown-btn {
        background-color: #1f2937;
        color: #9ca3af;
    }
    .dark .action-dropdown-btn:hover {
        background-color: #374151;
        color: #f3f4f6;
    }
    .action-dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 4px;
        min-width: 160px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid #e5e7eb;
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
    }
    .action-dropdown.open .action-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .dark .action-dropdown-menu {
        background: #1f2937;
        border-color: #374151;
    }
    .action-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 14px;
        color: #374151;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .action-dropdown-item:first-child {
        border-radius: 10px 10px 0 0;
    }
    .action-dropdown-item:last-child {
        border-radius: 0 0 10px 10px;
    }
    .action-dropdown-item:hover {
        background-color: #f3f4f6;
    }
    .dark .action-dropdown-item {
        color: #d1d5db;
    }
    .dark .action-dropdown-item:hover {
        background-color: #374151;
    }
    .action-dropdown-item.text-blue {
        color: #2563eb;
    }
    .action-dropdown-item.text-green {
        color: #16a34a;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2 text-white" style="color: white !important;">
                    <i class="ri-stack-fill mr-2"></i>Stok Bapok
                </h1>
                <p class="text-white/90 text-sm md:text-base" style="color: rgba(255,255,255,0.9) !important;">
                    @if(isset($pasar))
                        Data stok bahan pokok di {{ $pasar->nama_pasar }}
                    @else
                        Data stok bahan pokok semua pasar
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- <button type="button" id="btn-print" title="Print"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition border border-white/30">
                    <i class="ri-printer-line text-lg"></i>
                    <span>Print</span>
                </button> -->
                <a href="{{ route('pasar.stok-bapok.export-excel', ['pasarSlug' => $pasarSlug, 'tanggal' => request('tanggal')]) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition border border-white/30">
                    <i class="ri-file-excel-2-line text-lg"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="ri-filter-3-line text-gray-500 dark:text-gray-400"></i>
                <span class="font-medium text-gray-700 dark:text-gray-300">Filter</span>
            </div>
            <div class="flex flex-wrap gap-3">
                <!-- Filter Status -->
                <select id="filter-status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
                <input type="date" id="filter-tanggal" value="{{ $selectedDate ?? request('tanggal') }}"
                    class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <!-- Reset Button -->
                <button type="button" id="btn-reset-filter"
                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm font-medium">
                    <i class="ri-refresh-line mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @php
        $totalKomoditas = $komoditasList->total() ?? 0;
        $terisiCount = collect($komoditasList->items())->filter(fn($item) => !is_null($item->stok))->count();
        $pendingCount = collect($komoditasList->items())->filter(fn($item) => in_array($item->status ?? '', ['pending', 'draft']))->count();
        $verifiedCount = collect($komoditasList->items())->filter(fn($item) => ($item->status ?? '') === 'verified')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0c1427] rounded-xl p-4 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="ri-shopping-basket-2-line text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKomoditas }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Komoditas</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#0c1427] rounded-xl p-4 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="ri-check-double-line text-2xl text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $verifiedCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terverifikasi</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#0c1427] rounded-xl p-4 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <i class="ri-time-line text-2xl text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#0c1427] rounded-xl p-4 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="ri-stack-line text-2xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $terisiCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stok Terisi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
        <!-- Table Header with controls -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px]">
            <!-- Top row: Title and Confirm button -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="trezo-card-title">
                    <h5 class="mb-0 text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="ri-table-line mr-2 text-emerald-500"></i>Browse Data Stok
                    </h5>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-confirm-all"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg transition shadow-sm hover:bg-emerald-700">
                        <i class="ri-check-double-fill"></i>
                        <span>Confirm All</span>
                    </button>
                </div>
            </div>
            
            <!-- Bottom row: Showing rows, Search, Page length -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <!-- Left: Showing rows -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Tampilkan</span>
                    <select id="page-length" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600 dark:text-gray-400">data</span>
                </div>
                
                <!-- Right: Search -->
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Cari komoditas..."
                        class="pl-10 pr-4 py-2.5 w-full sm:w-72 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="trezo-card-content">
            <div class="overflow-x-auto">
                <table id="stok-bapok-table" class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 50px;">
                                <input type="checkbox" id="select-all-checkbox" 
                                    class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 60px;">No</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Komoditas</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Stok</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Status Integrasi</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Created By</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($komoditasList ?? [] as $index => $item)
                        <tr data-komoditas-id="{{ $item->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                    data-status="{{ $item->status ?? 'pending' }}"
                                    class="item-checkbox w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                            </td>
                            <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-100 font-medium">
                                {{ ($komoditasList->currentPage() - 1) * $komoditasList->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-100">
                                <span class="font-medium">{{ $item->nama_komoditas ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400 tanggal-cell">
                                @php
                                    $tanggalVal = $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-';
                                @endphp
                                {{ $tanggalVal }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-gray-100 stok-cell">
                                @php
                                    $satuanDisplay = strtolower($item->satuan) === 'liter' ? 'L' : ($item->satuan ?? 'kg');
                                @endphp
                                @if(!is_null($item->stok))
                                    {{ number_format($item->stok, 0, ',', '.') }} {{ $satuanDisplay }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $status = $item->status ?? 'draft';
                                @endphp
                                @if($status === 'verified')
                                    <span class="status-badge status-sukses">✔ Sukses</span>
                                @elseif($status === 'draft')
                                    <span class="status-badge status-draft">📝 Draft</span>
                                @elseif($status === 'pending')
                                    <span class="status-badge status-pending">⏳ Pending</span>
                                @else
                                    <span class="status-badge status-gagal">✖ Gagal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                                {{ $item->created_by ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <div class="action-dropdown">
                                        <button type="button" class="action-dropdown-btn" title="Actions">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                        <div class="action-dropdown-menu">
                                            @php
                                                $stokForJs = $item->stok ?? '';
                                                $tanggalForJs = $item->tanggal ?? '';
                                            @endphp
                                            <div class="action-dropdown-item text-blue" onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $stokForJs }}', '{{ $item->satuan ?? '' }}', '{{ $tanggalForJs }}', '{{ $item->status ?? 'pending' }}', '{{ $item->created_by ?? '-' }}')">
                                                <i class="ri-eye-line"></i>
                                                <span>View Detail</span>
                                            </div>
                                            <div class="action-dropdown-item text-green" onclick="showEditStokModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $stokForJs }}', '{{ $item->satuan ?? 'kg' }}')">
                                                <i class="ri-pencil-line"></i>
                                                <span>Edit Stok</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="ri-inbox-2-fill text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-lg font-medium">Belum ada data</p>
                                    <p class="text-sm">Data stok bapok akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($komoditasList) && $komoditasList instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Show {{ $komoditasList->firstItem() ?? 0 }} to {{ $komoditasList->lastItem() ?? 0 }} total {{ $komoditasList->total() }}
                </div>
                <div class="flex items-center gap-1">
                    {{ $komoditasList->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="modalConfirmStok" class="hidden fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full shadow-xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-check-double-fill text-3xl text-emerald-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Stok</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Anda akan mempublikasikan perubahan stok berikut:</p>
        </div>
        
        <!-- Review Items Container -->
        <div id="review-items-container" class="mb-6 max-h-60 overflow-y-auto bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-left">
            <div class="text-center text-gray-500 italic py-2">Memuat data...</div>
        </div>

        <div class="flex gap-3">
            <button type="button" id="btn-cancel-confirm" class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
            <button type="button" id="btn-yes-confirm" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition">Ya, Konfirmasi</button>
        </div>
    </div>
</div>

<!-- Modal Edit Stok -->
<div id="editStokModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Edit Stok</h3>
        
        <input type="hidden" id="editKomoditasId">
        
        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Komoditas</label>
            <div id="editNamaKomoditas" class="font-bold text-gray-900 dark:text-white"></div>
        </div>
        
        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Stok Lama</label>
            <div id="editStokLama" class="text-gray-600 dark:text-gray-400"></div>
        </div>
        
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Stok Baru</label>
            <div class="flex items-center gap-2 mt-1">
                <input id="stok_baru_display" type="text" 
                    class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-left bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="0"
                    oninput="formatStokInput(this)">
                <input id="stok_baru" type="hidden">
                <span id="editSatuanDisplay" class="text-gray-500 font-medium text-sm">kg</span>
            </div>
        </div>

        <div class="mb-4">
            <label for="editTanggalStok" class="text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pendataan</label>
            <input id="editTanggalStok" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="flex gap-2 justify-end">
            <button type="button" onclick="closeEditStokModal()" 
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400">Batal</button>
            <button type="button" id="btnSimpanStok" 
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Simpan</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastSuccess" class="fixed top-5 right-5 z-[9999] hidden bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg">
    ✅ Stok berhasil diperbarui
</div>

<!-- Modal View Detail -->
<div id="detailModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg shadow-xl mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="ri-information-line mr-2 text-blue-500"></i>Detail Stok Komoditas
            </h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <span class="text-sm text-gray-500 dark:text-gray-400">Nama Komoditas</span>
                <span id="detailNamaKomoditas" class="font-semibold text-gray-900 dark:text-white"></span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <span class="text-sm text-gray-500 dark:text-gray-400">Stok Saat Ini</span>
                <span id="detailStok" class="font-semibold text-emerald-600 dark:text-emerald-400"></span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Update</span>
                <span id="detailTanggal" class="text-gray-600 dark:text-gray-300"></span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                <span id="detailStatus"></span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat Oleh</span>
                <span id="detailCreatedBy" class="text-gray-600 dark:text-gray-300"></span>
            </div>
        </div>
        
        <div class="flex gap-2 justify-end mt-6">
            <button type="button" onclick="closeDetailModal()" 
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<script>
const csrfToken = '{{ csrf_token() }}';
const updateStokUrl = '{{ route("pasar.stok-bapok.update", $pasarSlug) }}';
const publishUrl = '{{ route("pasar.stok-bapok.publish", $pasarSlug) }}';
const getPendingUrl = '{{ route("pasar.stok-bapok.get-pending", $pasarSlug) }}';
const pasarSlug = '{{ $pasarSlug }}';

// Format number with thousand separator
function formatNumber(angka) {
    if (!angka) return '';
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseNumber(str) {
    if (!str) return '';
    return str.replace(/\./g, '');
}

function formatStokInput(input) {
    let value = input.value.replace(/\D/g, '');
    document.getElementById('stok_baru').value = value;
    input.value = formatNumber(value);
}

// Action dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const actionDropdowns = document.querySelectorAll('.action-dropdown');
    actionDropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.action-dropdown-btn');
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            actionDropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-dropdown')) {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });

    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const btnConfirmAll = document.getElementById('btn-confirm-all');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = [...itemCheckboxes].every(c => c.checked);
            const someChecked = [...itemCheckboxes].some(c => c.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        });
    });

    // Filter functionality
    const filterStatus = document.getElementById('filter-status');
    const filterTanggal = document.getElementById('filter-tanggal');
    const btnResetFilter = document.getElementById('btn-reset-filter');
    const pageLength = document.getElementById('page-length');

    function applyFilters() {
        const url = new URL(window.location.href);
        if (filterStatus && filterStatus.value) {
            url.searchParams.set('status', filterStatus.value);
        } else {
            url.searchParams.delete('status');
        }
        if (filterTanggal && filterTanggal.value) {
            url.searchParams.set('tanggal', filterTanggal.value);
        } else {
            url.searchParams.delete('tanggal');
        }
        window.location.href = url.toString();
    }

    if (filterStatus) filterStatus.addEventListener('change', applyFilters);
    if (filterTanggal) filterTanggal.addEventListener('change', applyFilters);
    
    if (btnResetFilter) {
        btnResetFilter.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            url.searchParams.delete('tanggal');
            window.location.href = url.toString();
        });
    }

    if (pageLength) {
        pageLength.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    // Search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#stok-bapok-table tbody tr');
            rows.forEach(row => {
                const nama = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                row.style.display = nama.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Confirm button - confirm all items
    if (btnConfirmAll) {
        btnConfirmAll.addEventListener('click', function() {
            // Get all komoditas IDs from the table
            const allIds = Array.from(document.querySelectorAll('.item-checkbox')).map(cb => cb.value);
            if (allIds.length === 0) {
                alert('Tidak ada data untuk dikonfirmasi');
                return;
            }
            
            loadPendingItemsForConfirm(allIds);
            document.getElementById('modalConfirmStok').classList.remove('hidden');
        });
    }

    // Cancel confirm
    document.getElementById('btn-cancel-confirm')?.addEventListener('click', function() {
        document.getElementById('modalConfirmStok').classList.add('hidden');
    });

    // Yes confirm - confirm all items
    document.getElementById('btn-yes-confirm')?.addEventListener('click', function() {
        const allIds = Array.from(document.querySelectorAll('.item-checkbox')).map(cb => cb.value);
        publishStok(allIds);
    });
});

function getSelectedKomoditasIds() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    return Array.from(checked).map(cb => cb.value);
}

function loadPendingItemsForConfirm(selectedIds) {
    const container = document.getElementById('review-items-container');
    container.innerHTML = '<div class="text-center text-gray-500 italic py-2">Memuat data...</div>';
    
    fetch(getPendingUrl)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.items.length > 0) {
                const filteredItems = data.items.filter(item => selectedIds.includes(String(item.komoditas_id)));
                if (filteredItems.length > 0) {
                    let html = '<ul class="space-y-2">';
                    filteredItems.forEach(item => {
                        html += `<li class="flex justify-between items-center p-2 bg-white dark:bg-gray-700 rounded">
                            <span class="font-medium">${item.nama_komoditas}</span>
                            <span class="text-emerald-600 font-semibold">${item.stok_display}</span>
                        </li>`;
                    });
                    html += '</ul>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="text-center text-gray-500 italic py-2">Tidak ada item pending yang dipilih</div>';
                }
            } else {
                container.innerHTML = '<div class="text-center text-gray-500 italic py-2">Tidak ada item pending</div>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="text-center text-red-500 italic py-2">Gagal memuat data</div>';
        });
}

function publishStok(komoditasIds) {
    fetch(publishUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ komoditas_ids: komoditasIds })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('modalConfirmStok').classList.add('hidden');
        if (data.success) {
            showToast('Data stok berhasil diverifikasi');
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        alert('Error server');
    });
}

// Edit Stok Modal
function showEditStokModal(id, nama, stokLama, satuan) {
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));
    
    const satuanDisplay = satuan?.toLowerCase() === 'liter' ? 'L' : (satuan || 'kg');
    
    document.getElementById('editKomoditasId').value = id;
    document.getElementById('editNamaKomoditas').innerText = nama;
    document.getElementById('editStokLama').innerText = stokLama ? Number(stokLama).toLocaleString('id-ID') + ' ' + satuanDisplay : '-';
    document.getElementById('editSatuanDisplay').innerText = satuanDisplay;
    
    document.getElementById('stok_baru').value = '';
    document.getElementById('stok_baru_display').value = '';
    document.getElementById('editStokModal').classList.remove('hidden');
}

function closeEditStokModal() {
    document.getElementById('editStokModal').classList.add('hidden');
}

// Save Stok
document.getElementById('btnSimpanStok')?.addEventListener('click', function() {
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('komoditas_id', document.getElementById('editKomoditasId').value);
    formData.append('stok_baru', document.getElementById('stok_baru').value);
    formData.append('tanggal', document.getElementById('editTanggalStok').value);

    fetch(updateStokUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (!res.success) {
            alert('Gagal update: ' + (res.message || 'Terjadi kesalahan'));
            return;
        }

        const row = document.querySelector(`[data-komoditas-id="${res.komoditas_id}"]`);
        if (row) {
            const stokCell = row.querySelector('.stok-cell');
            if (stokCell) stokCell.innerText = res.stok_display;
            row.querySelector('.tanggal-cell').innerText = res.tanggal;
            const statusCell = row.querySelector('td:nth-child(6)');
            if (statusCell) {
                statusCell.innerHTML = '<span class="status-badge status-draft">📝 Draft</span>';
            }
        }

        showToast();
        closeEditStokModal();
    })
    .catch(() => alert('Error server'));
});

// Detail Modal
function showDetailModal(id, nama, stok, satuan, tanggal, status, createdBy) {
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));
    
    const satuanDisplay = satuan?.toLowerCase() === 'liter' ? 'L' : (satuan || 'kg');
    
    document.getElementById('detailNamaKomoditas').innerText = nama;
    document.getElementById('detailStok').innerText = stok ? Number(stok).toLocaleString('id-ID') + ' ' + satuanDisplay : '-';
    document.getElementById('detailTanggal').innerText = tanggal || '-';
    document.getElementById('detailCreatedBy').innerText = createdBy || '-';
    
    const statusEl = document.getElementById('detailStatus');
    if (status === 'verified') {
        statusEl.innerHTML = '<span class="status-badge status-sukses">✔ Sukses</span>';
    } else if (status === 'draft') {
        statusEl.innerHTML = '<span class="status-badge status-draft">📝 Draft</span>';
    } else if (status === 'pending') {
        statusEl.innerHTML = '<span class="status-badge status-pending">⏳ Pending</span>';
    } else {
        statusEl.innerHTML = '<span class="status-badge status-gagal">✖ Gagal</span>';
    }
    
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function showToast(message) {
    const toast = document.getElementById('toastSuccess');
    if (message) toast.innerHTML = '✅ ' + message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
}

// Print functionality
document.getElementById('btn-print')?.addEventListener('click', function() {
    window.print();
});
</script>
@endpush
