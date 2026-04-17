@extends('layouts.admin.master')

@section('title', 'Update Harga')

@section('breadcrumb')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/datatables-2.3.4/datatables.tailwindcss.css') }}">
<style>
    /* Style untuk input date agar placeholder dan icon berwarna abu-abu */
    input[type="date"] {
        color: #6b7280; /* text-gray-500 */
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(50%) sepia(0%) saturate(0%) brightness(90%);
    }
    input[type="date"]::-webkit-datetime-edit {
        color: #9ca3af; /* text-gray-400 */
    }
    input[type="date"]::-webkit-datetime-edit-fields-wrapper {
        color: #9ca3af;
    }
    input[type="date"]:valid {
        color: #111827; /* text-gray-900 ketika sudah ada value */
    }
    input[type="date"]:valid::-webkit-datetime-edit {
        color: #111827;
    }
    .dark input[type="date"]:valid {
        color: #f3f4f6;
    }
    .dark input[type="date"]:valid::-webkit-datetime-edit {
        color: #f3f4f6;
    }
    /* Custom table style for komoditas table */
    .komoditas-table th,
    .komoditas-table td {
        vertical-align: middle !important;
        /* Center secara vertikal */
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    .komoditas-table th,
    .komoditas-table td:not(:first-child) {
        text-align: center;
    }
    .komoditas-table td:first-child {
        text-align: center;
    }
    .komoditas-table .komoditas-cell {
        padding-right: 10px !important;
        padding-left: 10px !important;
        min-width: 160px;
        text-align: center !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .komoditas-table .komoditas-cell .komoditas-nama {
        text-align: left;
        align-self: flex-start;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0 auto;
    }
    .komoditas-table .harga-cell {
        padding-left: 0 !important;
        padding-right: 0 !important;
        min-width: 110px;
    }
    .komoditas-table td, .komoditas-table th {
        font-size: 15px;
    }
    .komoditas-table tbody tr {
        transition: background 0.2s;
    }
    .komoditas-table tbody tr:hover {
        background: #f3f4f6;
    }
    .dark .komoditas-table tbody tr:hover {
        background: #1e293b;
    }
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
        pointer-events: auto;
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
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header Harga Komoditas -->
    <div class="page-header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2 text-white" style="color: white !important;">
                    <i class="ri-price-tag-3-fill mr-2"></i>Update Harga Komoditas
                </h1>
                <p class="text-white/90 text-sm md:text-base" style="color: rgba(255,255,255,0.9) !important;">
                    Data harga komoditas di {{ $namaPasar ?? 'Pasar' }}
                </p>
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
                <select id="filter-status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-left filter-status-select">
                    <style>
                        /* Biar tulisan dan icon select filter status lebih ke kiri */
                        .filter-status-select {
                            background-position: right 1.2rem center !important;
                            text-align: left !important;
                            font-size: 0.875rem !important;
                        }
                        .filter-status-select option {
                            text-align: left;
                            font-size: 0.875rem !important;
                        }
                    </style>
                    <option value="">Semua Status</option>
                    <option value="sukses">Sukses</option>
                    <option value="pending">Pending</option>
                    <option value="gagal">Gagal</option>
                </select>
                <input type="date" id="filter-tanggal" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <button class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm font-medium">
                    <i class="ri-refresh-line mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="trezo-card-title">
                <h5 class="mb-0 text-lg font-semibold text-gray-900 dark:text-white">
                    Browse Data
                </h5>
            </div>
            <div>
                <button type="button" onclick="showUpdateAllModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg shadow hover:bg-emerald-700 transition-all text-sm">
                    <i class="ri-edit-box-line"></i>
                    Update Semua Harga
                </button>
            </div>
        </div>
        <div class="trezo-card-content">
            <div class="overflow-x-auto">
                <table id="harga-bapok-table" class="w-full komoditas-table">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="pl-4 pr-2 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 48px;">No</th>
                            <th class="komoditas-cell text-left font-semibold text-gray-700 dark:text-gray-300">Komoditas</th>
                            <th class="harga-cell font-semibold text-gray-700 dark:text-gray-300">Harga</th>
                            <th class="font-semibold text-gray-700 dark:text-gray-300">Tanggal Update</th>
                            <th class="font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="font-semibold text-gray-700 dark:text-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($komoditasList as $index => $item)
                        <tr data-komoditas-id="{{ $item->id }}"
                            class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="pl-4 pr-2 py-3 text-center text-gray-900 dark:text-gray-100 font-medium">
                                {{ $loop->iteration }}
                            </td>
                            <td class="komoditas-cell text-gray-900 dark:text-gray-100">
                                <span class="komoditas-nama font-medium">{{ $item->nama_komoditas ?? '-' }}</span>
                            </td>
                            <td class="harga-cell font-medium text-gray-900 dark:text-gray-100">
                                @php
                                    $hargaVal = is_array($item->harga) ? ($item->harga['harga'] ?? null) : $item->harga;
                                @endphp
                                @if(!is_null($hargaVal))
                                    Rp{{ number_format($hargaVal, 0, ',', '.') }}
                                    @if($item->satuan)/{{ $item->satuan }}@endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-gray-600 dark:text-gray-400 tanggal-cell">
                                @php
                                    $tanggalVal = is_array($item->harga) ? ($item->harga['tanggal'] ?? null) : ($item->tanggal ?? null);
                                @endphp
                                {{ $tanggalVal ? \Carbon\Carbon::parse($tanggalVal)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @php
                                    $status = $item->status ?? 'pending';
                                @endphp
                                @if($status === 'verified')
                                    <span class="status-badge status-sukses">✔ Sukses</span>
                                @elseif(in_array($status, ['pending','draft']))
                                    <span class="status-badge status-pending">⏳ Pending</span>
                                @else
                                    <span class="status-badge status-gagal">✖ Gagal</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <div class="action-dropdown">
                                        <button type="button" class="action-dropdown-btn" title="Actions">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                        <div class="action-dropdown-menu">
                                            @php
                                                $hargaForJs = is_array($item->harga) ? ($item->harga['harga'] ?? '') : ($item->harga ?? '');
                                                $tanggalForJs = is_array($item->harga) ? ($item->harga['tanggal'] ?? '') : ($item->tanggal ?? '');
                                            @endphp
                                            <div class="action-dropdown-item text-blue" onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $hargaForJs }}', '{{ $item->satuan ?? '' }}', '{{ $item->harga_acuan ?? '' }}', '{{ $tanggalForJs }}', '{{ $item->status ?? 'pending' }}', '{{ Auth::user()->name ?? '-' }}')">
                                                <i class="ri-eye-line"></i>
                                                <span>View Detail</span>
                                            </div>
                                            <div class="action-dropdown-item text-green" onclick="showEditHargaModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $hargaForJs }}')">
                                                <i class="ri-pencil-line"></i>
                                                <span>Edit Harga</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="ri-inbox-2-fill text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-lg font-medium">Tidak ada Data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <div id="toastSuccess"
                        class="fixed top-5 right-5 z-[9999] hidden bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg">
                        ✅ Harga berhasil diperbarui
                    </div>
                </table>
            </div>
        </div>
    </div>



</div>
    <!-- Modal View Detail -->
    <div id="detailModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg shadow-xl mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    <i class="ri-information-line mr-2 text-blue-500"></i>Detail Komoditas
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
                    <span class="text-sm text-gray-500 dark:text-gray-400">Harga Saat Ini</span>
                    <span id="detailHarga" class="font-semibold text-emerald-600 dark:text-emerald-400"></span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Harga Acuan</span>
                    <span id="detailHargaAcuan" class="font-medium text-gray-600 dark:text-gray-300"></span>
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
<!-- Modal Edit Harga -->

<div id="editHargaModal"
     class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div id="formEditHarga" class="bg-white rounded-xl p-6 w-full max-w-md">
    @csrf

    <input type="hidden" name="komoditas_id" id="editKomoditasId">

    <h3 class="text-lg font-semibold mb-4">Edit Harga</h3>

    <div class="mb-3">
        <label class="text-sm font-medium">Komoditas</label>
        <div id="editNamaKomoditas" class="font-bold"></div>
    </div>

    <div class="mb-3">
        <label class="text-sm font-medium">Harga Lama</label>
        <div id="editHargaLama"></div>
    </div>

    <div class="mb-4">
        <label class="text-sm font-medium">Harga Baru</label>
        <div class="flex items-center gap-2">
            <span class="text-gray-500 font-medium text-sm">Rp</span>
            <input id="harga_baru_display"
                type="text"
                class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-left bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="0"
                oninput="formatRupiahInput(this, 'harga_baru')">
            <input id="harga_baru" name="harga_baru" type="hidden">
        </div>
    </div>
    <div class="mb-4">
        <label for="editTanggalHarga" class="text-sm font-medium">Tanggal Harga</label>
        <div class="relative">
            <input id="editTanggalHarga" name="editTanggalHarga" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg pl-3 pr-3 py-2 text-center bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
        </div>
    </div>

    <div class="flex gap-2 justify-end">
        <button type="button" onclick="closeEditHargaModal()"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">Batal</button>
        <button type="button"
            id="btnSimpanHarga"
            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all">
            Simpan
        </button>
    </div>
</div>

</div>

<!-- Modal Update Semua Harga -->
<div id="updateAllModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-3xl shadow-2xl flex flex-col max-h-[85vh]">
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="ri-edit-box-line mr-2 text-emerald-600"></i>Update Semua Harga
            </h3>
            <button onclick="closeUpdateAllModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content dengan scroll -->
        <div class="px-4 pt-4">
            <p class="text-gray-600 dark:text-gray-400 text-xs mb-3">Masukkan harga baru untuk setiap komoditas. Kosongkan jika tidak ingin mengubah harga.</p>

            <!-- Header tabel fixed -->
            <table class="w-full text-sm table-fixed">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 28%;">Komoditas</th>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 22%;">Harga Lama</th>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 22%;">Harga Baru</th>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 28%;">Tanggal</th>
                    </tr>
                </thead>
            </table>
        </div>
        
        <!-- Body tabel scrollable -->
        <div class="flex-1 overflow-y-auto px-4 pb-4" style="max-height: 50vh;">
            <table class="w-full text-sm table-fixed">
                <colgroup>
                    <col style="width: 28%;">
                    <col style="width: 22%;">
                    <col style="width: 22%;">
                    <col style="width: 28%;">
                </colgroup>
                <tbody id="updateAllTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                    <!-- Diisi via JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex gap-2 justify-end p-4 border-t border-gray-200 dark:border-gray-700">
            <button type="button" onclick="closeUpdateAllModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                Batal
            </button>
            <button type="button" id="btnSimpanSemuaHarga" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="ri-save-line"></i>
                <span id="btnSimpanSemuaText">Simpan Semua</span>
                <span id="btnSimpanSemuaLoading" class="hidden">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

{{-- Configuration for JS --}}
<div id="lurah-bapok-config" class="hidden"
    data-csrf-token="{{ csrf_token() }}"
    data-update-harga-url="{{ route('lurah.komoditas.update-harga') }}">
</div>

<script>
    // Data komoditas dari server untuk fitur Update Semua
    window.lurahKomoditasData = @json($komoditasJson ?? []);
</script>

@push('scripts')
<script src="{{ asset('assets/admin/js/lurah-bapok.js') }}"></script>
<script>
    // Animasi data loading (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.animation = `slideUp 0.6s ease forwards`;
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>


<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@endsection
