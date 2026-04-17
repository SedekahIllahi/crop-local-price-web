@extends('layouts.admin.master')

@section('title', 'Harga Bapok')

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
                    <span class="ms-1 text-sm font-medium text-primary-500 dark:text-primary-400">Harga Bapok</span>
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
    .action-btn-delete {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .action-btn-delete:hover {
        background-color: #dc2626;
        color: white;
    }
    .dark .action-btn-view {
        background-color: rgba(37, 99, 235, 0.2);
    }
    .dark .action-btn-edit {
        background-color: rgba(22, 163, 74, 0.2);
    }
    .dark .action-btn-delete {
        background-color: rgba(220, 38, 38, 0.2);
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
        position: fixed;
        min-width: 160px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid #e5e7eb;
        z-index: 9999;
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
    .action-dropdown-item.text-red {
        color: #dc2626;
    }
    .action-dropdown-item.text-red:hover {
        background-color: #fef2f2;
    }
    .dark .action-dropdown-item.text-red:hover {
        background-color: rgba(220, 38, 38, 0.1);
    }
    .action-dropdown-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 4px 0;
    }
    .dark .action-dropdown-divider {
        background: #374151;
    }
    
    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .page-header {
            background: #059669 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .status-badge {
            padding: 4px 8px;
        }
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
                    <i class="ri-price-tag-3-fill mr-2"></i>Harga Bapok
                </h1>
                <p class="text-white/90 text-sm md:text-base" style="color: rgba(255,255,255,0.9) !important;">
                    @if(isset($pasar))
                        Data harga bahan pokok di {{ $pasar->nama_pasar }}
                    @else
                        Data harga bahan pokok semua pasar
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- <button type="button" id="btn-print" title="Print (Ctrl+P)"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition border border-white/30">
                    <i class="ri-printer-line text-lg"></i>
                    <span>Print</span>
                </button> -->
                <a href="{{ route('pasar.harga-bapok.export-excel', ['pasarSlug' => $pasarSlug, 'tanggal' => request('tanggal')]) }}" target="_blank"
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
                <!-- Filter Pasar -->
                <!-- <select id="filter-pasar" data-current-pasar="{{ $pasar->id ?? '' }}" data-current-slug="{{ $pasarSlug ?? '' }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @php
                        $slugMap = [
                            'Pasar Bantul' => 'bantul',
                            'Pasar Niten' => 'niten',
                            'Pasar Imogiri' => 'imogiri',
                            'Pasar Piyungan' => 'piyungan',
                            'Pasar Pundong' => 'pundong',
                        ];
                    @endphp
                    @foreach($pasarList ?? [] as $p)
                        <option value="{{ $p->id }}" data-slug="{{ $slugMap[$p->nama_pasar] ?? strtolower(str_replace('Pasar ', '', $p->nama_pasar)) }}" {{ (isset($pasar) && $pasar->id === $p->id) ? 'selected' : '' }}>
                            {{ $p->nama_pasar }}
                        </option>
                        
                    @endforeach -->
                </select>
                <!-- Filter Status -->
                <select id="filter-status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
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
        $terisiCount = collect($komoditasList->items())->filter(fn($item) => !is_null($item->harga))->count();
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
                    <i class="ri-price-tag-3-line text-2xl text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $terisiCount }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Harga Terisi</p>
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
                        <i class="ri-table-line mr-2 text-emerald-500"></i>Browse Data
                    </h5>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-add-data"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <i class="ri-add-line text-lg"></i>
                        <span>Tambah Data</span>
                    </button>
                    <button type="button" id="btn-confirm-all" disabled
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-400 text-white font-medium rounded-lg transition shadow-sm cursor-not-allowed">
                        <i class="ri-check-double-fill"></i>
                        <span>Confirm (<span id="selected-count">0</span>)</span>
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
                <table id="harga-bapok-table" class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 50px;">
                                <input type="checkbox" id="select-all-checkbox" 
                                    class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 60px;">No</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Komoditas</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Harga</th>
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
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                                @php
                                    $tanggalVal = $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y H:i') : '-';
                                @endphp
                                {{ $tanggalVal }}
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-gray-100">
                                @if(!is_null($item->harga))
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}@if($item->satuan)/{{ $item->satuan }}@endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $status = $item->status ?? 'pending';
                                @endphp
                                @if($status === 'verified')
                                    <span class="status-badge status-sukses"><i class="ri-checkbox-circle-fill"></i> Sukses</span>
                                @elseif(in_array($status, ['pending','draft']))
                                    <span class="status-badge status-pending"><i class="ri-time-line"></i> Pending</span>
                                @else
                                    <span class="status-badge status-gagal"><i class="ri-close-circle-fill"></i> Gagal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                                {{ $item->created_by?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <div class="action-dropdown">
                                        <button type="button" class="action-dropdown-btn" title="Actions">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                        <div class="action-dropdown-menu">
                                            <div class="action-dropdown-item text-blue" onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $item->harga ?? '' }}', '{{ $item->satuan ?? '' }}', '{{ $item->harga_acuan ?? '' }}', '{{ $item->tanggal ?? '' }}', '{{ $item->status ?? 'pending' }}', '{{ $item->created_by->name ?? '-' }}')">
                                                <i class="ri-eye-line"></i>
                                                <span>View Detail</span>
                                            </div>
                                            <div class="action-dropdown-item text-green" onclick="showEditHargaModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $item->harga ?? '' }}')">
                                                <i class="ri-pencil-line"></i>
                                                <span>Edit Harga</span>
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
                                    <p class="text-sm">Data harga bapok akan muncul di sini</p>
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
            @else
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Show 1 to 10 total 0
                </div>
                <nav class="flex items-center gap-1">
                    <button class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-lg text-gray-400 cursor-not-allowed" disabled>&laquo;</button>
                    <button class="px-3 py-1.5 text-sm bg-emerald-500 text-white rounded-lg">1</button>
                    <button class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-lg text-gray-400 cursor-not-allowed" disabled>&raquo;</button>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="modalConfirmHarga" class="hidden fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full shadow-xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-check-double-fill text-3xl text-emerald-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Harga</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Anda akan mempublikasikan perubahan harga berikut:</p>
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

<!-- Modal Tambah Komoditas -->
<div id="modalAddKomoditas" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg shadow-xl mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="ri-add-circle-line mr-2 text-blue-500"></i>Tambah Komoditas Baru
            </h3>
            <button type="button" id="btn-close-add-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <form id="formAddKomoditas" enctype="multipart/form-data">
            <!-- Icon & Image Section -->
            <div class="mb-5 grid grid-cols-2 gap-4">
                <!-- Icon (Text/Emoji) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Emoji)</label>
                    <div class="flex flex-col items-center gap-2">
                        <div id="emoji-preview-main" class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center border-2 border-blue-200 dark:border-blue-700 text-3xl shadow-sm">
                            🛒
                        </div>
                        <input type="text" name="icon" id="input-emoji-main" value="" maxlength="10" placeholder="🛒"
                            class="w-20 h-10 text-center text-2xl border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            oninput="document.getElementById('emoji-preview-main').textContent = this.value || '🛒'">
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Ketik emoji / teks icon</p>
                    </div>
                </div>

                <!-- Upload Gambar/Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar/Foto</label>
                    <div class="flex flex-col items-center gap-2">
                        <div id="preview-container-image" class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden">
                            <i id="preview-placeholder-image" class="ri-image-add-line text-2xl text-gray-400"></i>
                            <img id="preview-image" src="" class="hidden w-full h-full object-cover">
                        </div>
                        <input type="file" name="image" id="input-image" accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden">
                        <label for="input-image" class="cursor-pointer inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs">
                            <i class="ri-upload-2-line"></i> Pilih Gambar
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Opsional</p>
                    </div>
                </div>
            </div>
            
            <!-- Nama Komoditas -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Komoditas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_komoditas" required placeholder="Contoh: Beras Premium"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Satuan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Satuan <span class="text-red-500">*</span></label>
                <input type="text" name="satuan" required placeholder="Contoh: kg, liter, butir"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Harga Acuan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Acuan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="number" name="harga_acuan" required min="0" placeholder="0"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Kategori -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori_id" required
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(\App\Models\KategoriKomoditas::all() as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 justify-end">
                <button type="button" id="btn-cancel-add" 
                    class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                <button type="submit" id="btn-submit-komoditas"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="ri-save-line"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

<!-- Modal Add Commodity -->
<div id="addDataModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Tambah Komoditas Baru</h3>
        <p class="text-sm text-gray-500 mb-4">Komoditas ini akan ditambahkan ke Master Data</p>
        
        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nama Komoditas</label>
            <input id="addNamaKomoditas" type="text" placeholder="Contoh: Beras Merah"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
        </div>

        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
            <select id="addKategoriId" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                <option value="">Pilih Kategori</option>
                @foreach($allKategori ?? [] as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                <input id="addSatuan" type="text" placeholder="Kg / Liter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Harga Acuan</label>
                <input id="addHargaAcuan" type="number" min="0" placeholder="Rp"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <!-- Icon & Image -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Icon (Emoji)</label>
                <div class="flex items-center gap-2 mt-1">
                    <div id="emoji-preview-secondary" class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center border border-blue-200 dark:border-blue-700 text-xl">
                        🛒
                    </div>
                    <input type="text" id="addIconEmoji" value="" maxlength="10" placeholder="🛒"
                        class="w-14 h-10 text-center text-xl border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-emerald-500"
                        oninput="document.getElementById('emoji-preview-secondary').textContent = this.value || '🛒'">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Gambar (Opsional)</label>
                <input id="addImage" type="file" accept="image/*"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-1.5 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs">
            </div>
        </div>
        
        <div class="flex gap-2 justify-end">
            <button type="button" onclick="closeAddDataModal()" 
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400">Batal</button>
            <button type="button" id="btnSimpanAddData" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Edit Harga -->
<div id="editHargaModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Edit Harga</h3>
        
        <input type="hidden" id="editKomoditasId">
        
        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Komoditas</label>
            <div id="editNamaKomoditas" class="font-bold text-gray-900 dark:text-white"></div>
        </div>
        
        <div class="mb-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Harga Lama</label>
            <div id="editHargaLama" class="text-gray-600 dark:text-gray-400"></div>
        </div>
        
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Harga Baru</label>
            <div class="relative mt-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                <input id="editHargaBaruDisplay" type="text" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 pl-10 text-center bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="0"
                    oninput="formatRupiahInputAdmin(this)">
                <input id="editHargaBaru" type="hidden">
            </div>
        </div>
        
        <div class="flex gap-2 justify-end">
            <button type="button" onclick="closeEditHargaModal()" 
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400">Batal</button>
            <button type="button" id="btnSimpanHarga" 
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal View Detail -->
<div id="detailModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-xl">
        <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Harga Komoditas</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Komoditas</label>
                <div id="detailNamaKomoditas" class="text-base font-bold text-gray-900 dark:text-white mt-1">-</div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Pasar</label>
                    <div id="detailHarga" class="text-base font-semibold text-emerald-600 mt-1">-</div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Acuan</label>
                    <div id="detailHargaAcuan" class="text-base font-semibold text-gray-700 dark:text-gray-300 mt-1">-</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Input</label>
                    <div id="detailTanggal" class="text-sm text-gray-800 dark:text-gray-200 mt-1">-</div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</label>
                    <div id="detailStatus" class="mt-1">-</div>
                </div>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diinput Oleh</label>
                <div id="detailCreatedBy" class="text-sm text-gray-800 dark:text-gray-200 mt-1">-</div>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button type="button" onclick="closeDetailModal()" 
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Tutup Dialog</button>
        </div>
    </div>
</div>

<!-- Configuration for JS -->
<div id="harga-bapok-config" class="hidden"
    data-csrf-token="{{ csrf_token() }}"
    data-update-harga-url="{{ route('admin.harga-bapok.update-harga') }}"
    data-publish-url="{{ route('pasar.harga-bapok.publish', ['pasarSlug' => $pasarSlug]) }}"
    data-get-pending-items-url="{{ route('admin.harga-bapok.get-pending-items') }}"
    data-store-komoditas-url="{{ route('admin.harga-bapok.store-komoditas') }}"
    data-pasar-id="{{ $pasar->id ?? '' }}"
    data-pasar-slug="{{ $pasarSlug ?? '' }}"
    data-pasar-name="{{ $pasar->nama_pasar ?? 'Semua Pasar' }}">
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/harga-bapok.js') }}"></script>
@endpush        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastSuccess" class="fixed top-5 right-5 z-[9999] hidden bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg">
    ✅ Harga berhasil diperbarui
</div>


