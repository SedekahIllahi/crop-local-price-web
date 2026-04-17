@extends('layouts.admin.master')

@section('title', 'Update Stok')

@section('breadcrumb')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/datatables-2.3.4/datatables.tailwindcss.css') }}">
<style>
    /* Style untuk input date agar placeholder dan icon berwarna abu-abu */
    input[type="date"] {
        color: #6b7280;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(50%) sepia(0%) saturate(0%) brightness(90%);
    }
    input[type="date"]::-webkit-datetime-edit {
        color: #9ca3af;
    }
    input[type="date"]::-webkit-datetime-edit-fields-wrapper {
        color: #9ca3af;
    }
    input[type="date"]:valid {
        color: #111827;
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
    .komoditas-table .stok-cell {
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
    .action-btn-edit {
        background-color: #dcfce7;
        color: #166534;
    }
    .action-btn-edit:hover {
        background-color: #16a34a;
        color: white;
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
</style>
@endpush

@section('content')
@php
    // Calculate 7-day confirmation logic
    $canConfirm = true;
    $daysRemaining = 0;
    $nextConfirmDate = null;
    if (isset($latestUpdatedAt) && $latestUpdatedAt) {
        $lastUpdate = \Carbon\Carbon::parse($latestUpdatedAt);
        $nextConfirmDate = $lastUpdate->copy()->addDays(7);
        $daysRemaining = (int) ceil(now()->diffInDays($nextConfirmDate, false));
        $canConfirm = $daysRemaining <= 0;
    }
@endphp
<div class="space-y-6">
    <!-- Page Header Stok Komoditas -->
    <div class="page-header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2 text-white" style="color: white !important;">
                    <i class="ri-stack-fill mr-2"></i>Update Stok Komoditas
                </h1>
                <p class="text-white/90 text-sm md:text-base" style="color: rgba(255,255,255,0.9) !important;">
                    Data stok komoditas di {{ $namaPasar ?? 'Pasar' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Confirmation Status Banner -->
    @if(isset($latestUpdatedAt) && $latestUpdatedAt)
    <div class="p-4 rounded-xl border {{ $canConfirm ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800' }}">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                @if($canConfirm)
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="ri-check-line text-xl text-emerald-600"></i>
                </div>
                @else
                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <i class="ri-time-line text-xl text-amber-600"></i>
                </div>
                @endif
            </div>
            <div class="flex-1">
                @if($canConfirm)
                <p class="font-medium text-emerald-800 dark:text-emerald-300">Data dapat diupdate</p>
                <p class="text-sm text-emerald-600 dark:text-emerald-400">Update terakhir: {{ \Carbon\Carbon::parse($latestUpdatedAt)->format('d M Y, H:i') }}</p>
                @else
                <p class="font-medium text-amber-800 dark:text-amber-300">Update stok belum tersedia</p>
                <p class="text-sm text-amber-600 dark:text-amber-400">
                    Update terakhir: {{ \Carbon\Carbon::parse($latestUpdatedAt)->format('d M Y, H:i') }} | 
                    Dapat diupdate pada: <strong>{{ $nextConfirmDate->format('d M Y') }}</strong> 
                    ({{ $daysRemaining }} hari lagi)
                </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="ri-filter-3-line text-gray-500 dark:text-gray-400"></i>
                <span class="font-medium text-gray-700 dark:text-gray-300">Filter</span>
            </div>
            <div class="flex flex-wrap gap-3">
                <select id="filter-status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-left">
                    <option value="">Semua Status</option>
                    <option value="sukses">Sukses</option>
                    <option value="pending">Pending</option>
                    <option value="gagal">Gagal</option>
                </select>
                <input type="date" id="filter-tanggal" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <button id="btn-reset-filter" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm font-medium">
                    <i class="ri-refresh-line mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
        @if($canConfirm)
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                <i class="ri-error-warning-line mr-1"></i>
                Update Stok Sebelum 23.59
            </span>
        </div>
        @endif
        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="trezo-card-title">
                <h5 class="mb-0 text-lg font-semibold text-gray-900 dark:text-white">
                    Browse Data Stok
                </h5>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btn-update-all-stok"
                    data-can-update="{{ $canConfirm ? 'true' : 'false' }}"
                    data-days-remaining="{{ $daysRemaining }}"
                    data-next-update-date="{{ $nextConfirmDate ? $nextConfirmDate->format('d M Y') : '' }}"
                    class="inline-flex items-center gap-2 px-4 py-2 {{ $canConfirm ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-400 cursor-not-allowed' }} text-white font-medium rounded-lg shadow transition-all text-sm">
                    <i class="ri-edit-box-line"></i>
                    Update Semua Stok
                </button>
            </div>
        </div>
        <div class="trezo-card-content">
            <div class="overflow-x-auto">
                <table id="stok-bapok-table" class="w-full komoditas-table">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="pl-4 pr-2 py-3 text-center font-semibold text-gray-700 dark:text-gray-300" style="width: 48px;">No</th>
                            <th class="komoditas-cell text-left font-semibold text-gray-700 dark:text-gray-300">Komoditas</th>
                            <th class="stok-cell font-semibold text-gray-700 dark:text-gray-300">Stok</th>
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
                            <td class="stok-cell font-medium text-gray-900 dark:text-gray-100">
                                @php
                                    $stokVal = $item->stok;
                                    $satuanDisplay = strtolower($item->satuan) === 'liter' ? 'L' : ($item->satuan ?? 'kg');
                                @endphp
                                @if(!is_null($stokVal))
                                    {{ number_format($stokVal, 0, ',', '.') }} {{ $satuanDisplay }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-gray-600 dark:text-gray-400 tanggal-cell">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
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
                                                $stokForJs = $item->stok ?? '';
                                                $tanggalForJs = $item->tanggal ?? '';
                                            @endphp
                                            <div class="action-dropdown-item text-blue" onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama_komoditas }}', '{{ $stokForJs }}', '{{ $item->satuan ?? '' }}', '{{ $tanggalForJs }}', '{{ $item->status ?? 'pending' }}', '{{ Auth::user()->name ?? '-' }}')">
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
                </tbody>
            </table>
            <div id="toastSuccess"
                class="fixed top-5 right-5 z-[9999] hidden bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg">
                ✅ Stok berhasil diperbarui
            </div>
                </table>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#stok-bapok-table tbody tr');
    const filterStatus = document.getElementById('filter-status');
    const filterTanggal = document.getElementById('filter-tanggal');
    const btnResetFilter = document.getElementById('btn-reset-filter');

    // Update All Stok Button Handler with 7-day restriction
    const btnUpdateAllStok = document.getElementById('btn-update-all-stok');
    if (btnUpdateAllStok) {
        btnUpdateAllStok.addEventListener('click', function () {
            if (!canUpdateStok) {
                iziToast.warning({
                    title: 'Tidak Dapat Update',
                    message: `Data hanya dapat diupdate 7 hari setelah update terakhir. Dapat diupdate pada tanggal ${nextUpdateDate} (${daysRemainingForUpdate} hari lagi).`,
                    position: 'topRight',
                    timeout: 5000
                });
                return;
            }
            showUpdateAllModal();
        });
    }

    // Tombol simpan di modal update semua stok
    const btnSimpanSemuaStok = document.getElementById('btnSimpanSemuaStok');
    if (btnSimpanSemuaStok) {
        btnSimpanSemuaStok.addEventListener('click', function () {
            const rows = document.querySelectorAll('#updateAllTableBody tr');
            const stokList = [];
            rows.forEach(row => {
                const input = row.querySelector('input.update-all-input');
                const komoditasId = input?.dataset.komoditasId;
                const stokBaru = input?.dataset.rawValue || '';
                if (komoditasId && stokBaru !== '') {
                    stokList.push({
                        komoditas_id: komoditasId,
                        stok_baru: stokBaru
                    });
                }
            });
            if (stokList.length === 0) {
                iziToast.error({ title: 'Error', message: 'Isi minimal satu stok komoditas.' });
                return;
            }
            btnSimpanSemuaStok.disabled = true;
            document.getElementById('btnSimpanSemuaText').classList.add('hidden');
            document.getElementById('btnSimpanSemuaLoading').classList.remove('hidden');
            fetch("{{ route('lurah.stok.updateAll') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ stok_list: stokList })
            })
            .then(res => res.json())
            .then(res => {
                btnSimpanSemuaStok.disabled = false;
                document.getElementById('btnSimpanSemuaText').classList.remove('hidden');
                document.getElementById('btnSimpanSemuaLoading').classList.add('hidden');
                if (!res.success) {
                    iziToast.error({ title: 'Gagal', message: res.message || 'Gagal update stok.' });
                    return;
                }
                iziToast.success({ title: 'Sukses', message: res.message });
                setTimeout(() => window.location.reload(), 1200);
            })
            .catch(() => {
                btnSimpanSemuaStok.disabled = false;
                document.getElementById('btnSimpanSemuaText').classList.remove('hidden');
                document.getElementById('btnSimpanSemuaLoading').classList.add('hidden');
                iziToast.error({ title: 'Error', message: 'Server error.' });
            });
        });
    }

    function applyFilters() {
        const statusValue = filterStatus?.value.toLowerCase() || '';
        const tanggalValue = filterTanggal?.value || '';
        let visibleCount = 0;

        tableRows.forEach(row => {
            if (row.classList.contains('no-data-row')) return;
            
            const statusCell = row.querySelector('.status-badge');
            const tanggalCell = row.querySelector('.tanggal-cell');
            let showRow = true;

            if (statusValue && statusCell) {
                let rowStatus = '';
                if (statusCell.classList.contains('status-sukses')) rowStatus = 'sukses';
                else if (statusCell.classList.contains('status-pending')) rowStatus = 'pending';
                else if (statusCell.classList.contains('status-gagal')) rowStatus = 'gagal';
                if (rowStatus !== statusValue) {
                    showRow = false;
                }
            }

            if (tanggalValue && tanggalCell) {
                const rowTanggalText = tanggalCell.textContent.trim();
                const rowTanggalParts = rowTanggalText.split(' ');
                if (rowTanggalParts.length === 3) {
                    const bulanMap = {
                        'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'Mei': '05', 'Jun': '06',
                        'Jul': '07', 'Agu': '08', 'Sep': '09', 'Okt': '10', 'Nov': '11', 'Des': '12',
                        'May': '05', 'Aug': '08', 'Oct': '10', 'Dec': '12'
                    };
                    let [tgl, bln, thn] = rowTanggalParts;
                    bln = bulanMap[bln] || bln;
                    const rowTanggalISO = `${thn}-${bln}-${tgl.padStart(2,'0')}`;
                    if (rowTanggalISO !== tanggalValue) {
                        showRow = false;
                    }
                } else {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        });

        let noDataRow = document.querySelector('.no-data-row');
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    if (filterStatus) filterStatus.addEventListener('change', applyFilters);
    if (filterTanggal) filterTanggal.addEventListener('change', applyFilters);
    if (btnResetFilter) {
        btnResetFilter.addEventListener('click', function() {
            if (filterStatus) filterStatus.value = '';
            if (filterTanggal) filterTanggal.value = '';
            applyFilters();
        });
    }

    // Action Dropdown functionality
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
        const items = dropdown.querySelectorAll('.action-dropdown-item');
        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-dropdown')) {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });
});
</script>
@endpush
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

<!-- Modal Edit Stok -->
<div id="editStokModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center">
    <div id="formEditStok" class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md">
        @csrf
        <input type="hidden" name="komoditas_id" id="editKomoditasId">

        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Stok</h3>

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
            <div class="flex items-center gap-2">
                <input id="stok_baru_display"
                    type="text"
                    class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-left bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="0"
                    oninput="formatStokInput(this, 'stok_baru')">
                <input id="stok_baru" name="stok_baru" type="hidden">
                <span id="editSatuanDisplay" class="text-gray-500 font-medium text-sm">kg</span>
            </div>
        </div>

        <div class="mb-4">
            <label for="editTanggalStok" class="text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pendataan</label>
            <div class="relative">
                <input id="editTanggalStok" name="editTanggalStok" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg pl-3 pr-3 py-2 text-center bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
            </div>
        </div>

        <div class="flex gap-2 justify-end">
            <button type="button" onclick="closeEditStokModal()"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">Batal</button>
            <button type="button" id="btnSimpanStok"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- Modal Update Semua Stok -->
<div id="updateAllModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-3xl shadow-2xl flex flex-col max-h-[85vh]">
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="ri-edit-box-line mr-2 text-emerald-600"></i>Update Semua Stok
            </h3>
            <button onclick="closeUpdateAllModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content dengan scroll -->
        <div class="px-4 pt-4">
            <p class="text-gray-600 dark:text-gray-400 text-xs mb-3">Masukkan stok baru untuk setiap komoditas. Kosongkan jika tidak ingin mengubah stok.</p>

            <!-- Header tabel fixed -->
            <table class="w-full text-sm table-fixed">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 28%;">Komoditas</th>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 22%;">Stok Lama</th>
                        <th class="text-left py-2 px-2 font-semibold text-gray-700 dark:text-gray-300" style="width: 22%;">Stok Baru</th>
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
            <button type="button" id="btnSimpanSemuaStok" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all flex items-center gap-2">
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

<script>
// Format angka dengan pemisah ribuan
function formatNumber(angka) {
    if (!angka) return '';
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseNumber(str) {
    if (!str) return '';
    return str.replace(/\./g, '');
}

function formatStokInput(input, hiddenId) {
    let value = input.value.replace(/\D/g, '');
    document.getElementById(hiddenId).value = value;
    input.value = formatNumber(value);
}

function formatStokInputBulk(input) {
    let value = input.value.replace(/\D/g, '');
    input.dataset.rawValue = value;
    input.value = formatNumber(value);
}

// Data komoditas untuk modal Update All
const komoditasData = @json($komoditasJson ?? []);

function showUpdateAllModal() {
    const tbody = document.getElementById('updateAllTableBody');
    tbody.innerHTML = '';

    komoditasData.forEach(item => {
        const satuanDisplay = item.satuan?.toLowerCase() === 'liter' ? 'L' : (item.satuan || 'kg');
        const stokFormatted = item.stok ? Number(item.stok).toLocaleString('id-ID') + ' ' + satuanDisplay : '-';
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
        row.innerHTML = `
            <td class="py-2 px-2">
                <span class="font-medium text-gray-900 dark:text-white text-sm">${item.nama}</span>
            </td>
            <td class="py-2 px-2 text-left">
                <span class="text-gray-600 dark:text-gray-400 text-sm">${stokFormatted}</span>
            </td>
            <td class="py-2 px-2">
                <div class="flex items-center">
                    <input type="text" 
                        data-komoditas-id="${item.id}" 
                        data-stok-lama="${item.stok || ''}"
                        data-satuan="${satuanDisplay}"
                        class="update-all-input w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-left text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="0"
                        oninput="formatStokInputBulk(this)">
                    <span class="text-gray-500 text-xs ml-1">${satuanDisplay}</span>
                </div>
            </td>
            <td class="py-2 px-2">
                <input type="date" 
                    class="update-all-date w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500" />
            </td>
        `;
        tbody.appendChild(row);
    });

    document.getElementById('updateAllModal').classList.remove('hidden');
    lockBodyScroll();
}

function closeUpdateAllModal() {
    document.getElementById('updateAllModal').classList.add('hidden');
    unlockBodyScroll();
}

// Tombol update semua stok
// Hapus event listener duplikat, handler sudah ada di bawah (hanya satu event listener yang boleh aktif)

// Tombol simpan di modal update semua stok
const btnSimpanSemuaStok = document.getElementById('btnSimpanSemuaStok');
if (btnSimpanSemuaStok) {
    btnSimpanSemuaStok.addEventListener('click', function () {
        const rows = document.querySelectorAll('#updateAllTableBody tr');
        const stokList = [];
        rows.forEach(row => {
            const input = row.querySelector('input.update-all-input');
            const komoditasId = input?.dataset.komoditasId;
            const stokBaru = input?.dataset.rawValue || '';
            if (komoditasId && stokBaru !== '') {
                stokList.push({
                    komoditas_id: komoditasId,
                    stok_baru: stokBaru
                });
            }
        });
        if (stokList.length === 0) {
            iziToast.error({ title: 'Error', message: 'Isi minimal satu stok komoditas.' });
            return;
        }
        btnSimpanSemuaStok.disabled = true;
        document.getElementById('btnSimpanSemuaText').classList.add('hidden');
        document.getElementById('btnSimpanSemuaLoading').classList.remove('hidden');
        fetch("{{ route('lurah.stok.updateAll') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ stok_list: stokList })
        })
        .then(res => res.json())
        .then(res => {
            btnSimpanSemuaStok.disabled = false;
            document.getElementById('btnSimpanSemuaText').classList.remove('hidden');
            document.getElementById('btnSimpanSemuaLoading').classList.add('hidden');
            if (!res.success) {
                iziToast.error({ title: 'Gagal', message: res.message || 'Gagal update stok.' });
                return;
            }
            iziToast.success({ title: 'Sukses', message: res.message });
            setTimeout(() => window.location.reload(), 1200);
        })
        .catch(() => {
            btnSimpanSemuaStok.disabled = false;
            document.getElementById('btnSimpanSemuaText').classList.remove('hidden');
            document.getElementById('btnSimpanSemuaLoading').classList.add('hidden');
            iziToast.error({ title: 'Error', message: 'Server error.' });
        });
    });
}

// Single edit stok
document.getElementById('btnSimpanStok').addEventListener('click', function () {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('komoditas_id', document.getElementById('editKomoditasId').value);
    formData.append('stok_baru', document.getElementById('stok_baru').value);
    formData.append('tanggal', document.getElementById('editTanggalStok').value);

    fetch("{{ route('lurah.stok.update') }}", {
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
            const statusCell = row.querySelector('td:nth-child(5)');
            if (statusCell) {
                statusCell.innerHTML = '<span class="status-badge status-draft">📝 Draft</span>';
            }
        }

        showToast();
        closeEditStokModal();
    })
    .catch(() => alert('Error server'));
});

function showToast() {
    const toast = document.getElementById('toastSuccess');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
}

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
    lockBodyScroll();
}

function closeEditStokModal() {
    document.getElementById('editStokModal').classList.add('hidden');
    unlockBodyScroll();
}

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

function lockBodyScroll() {
    const scrollY = window.scrollY || document.documentElement.scrollTop;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.dataset.scrollY = String(scrollY);
}

function unlockBodyScroll() {
    const stored = document.body.dataset.scrollY || '0';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.left = '';
    document.body.style.right = '';
    delete document.body.dataset.scrollY;
    window.scrollTo(0, parseInt(stored, 10) || 0);
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    if (event.target === modal) closeDetailModal();
});

// Global variable for 7-day restriction check
const canUpdateStok = document.getElementById('btn-update-all-stok')?.dataset.canUpdate === 'true';
const nextUpdateDate = document.getElementById('btn-update-all-stok')?.dataset.nextUpdateDate || '';
const daysRemainingForUpdate = parseInt(document.getElementById('btn-update-all-stok')?.dataset.daysRemaining) || 0;

// Override showEditStokModal to check 7-day restriction
const originalShowEditStokModal = showEditStokModal;
showEditStokModal = function(id, nama, stokLama, satuan) {
    if (!canUpdateStok) {
        iziToast.warning({
            title: 'Tidak Dapat Update',
            message: `Data hanya dapat diupdate 7 hari setelah update terakhir. Dapat diupdate pada tanggal ${nextUpdateDate} (${daysRemainingForUpdate} hari lagi).`,
            position: 'topRight',
            timeout: 5000
        });
        return;
    }
    originalShowEditStokModal(id, nama, stokLama, satuan);
};
</script>

@endsection
