/**
 * Lurah Bapok JavaScript
 * Consolidated from inline scripts in lurah.blade.php
 */

const LurahBapokConfig = {
    csrfToken: '',
    updateHargaUrl: '',

    init(element) {
        this.csrfToken = element.dataset.csrfToken || '';
        this.updateHargaUrl = element.dataset.updateHargaUrl || '';
    }
};

// ========================================
// Utility Functions
// ========================================

function formatRupiah(angka) {
    if (!angka) return '';
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseRupiah(str) {
    if (!str) return '';
    return str.replace(/\./g, '');
}

function formatRupiahInput(input, hiddenId) {
    let value = input.value.replace(/\D/g, ''); // Hanya angka
    if (hiddenId) {
        document.getElementById(hiddenId).value = value; // Simpan value asli ke hidden input
    }
    input.value = formatRupiah(value);
}

function formatRupiahInputBulk(input) {
    let value = input.value.replace(/\D/g, ''); // Hanya angka
    input.dataset.rawValue = value; // Simpan value asli ke data attribute
    input.value = formatRupiah(value);
}

function showToast() {
    const toast = document.getElementById('toastSuccess');
    if (toast) {
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    }
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

// ========================================
// Modal Functions
// ========================================

// --- Detail Modal ---
function showDetailModal(id, nama, harga, satuan, hargaAcuan, tanggal, status, createdBy) {
    // Close dropdowns first
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));

    document.getElementById('detailNamaKomoditas').innerText = nama;
    document.getElementById('detailHarga').innerText = harga ? 'Rp' + Number(harga).toLocaleString('id-ID') + (satuan ? '/' + satuan : '') : '-';
    document.getElementById('detailHargaAcuan').innerText = hargaAcuan ? 'Rp' + Number(hargaAcuan).toLocaleString('id-ID') + (satuan ? '/' + satuan : '') : '-';
    document.getElementById('detailTanggal').innerText = tanggal || '-';
    document.getElementById('detailCreatedBy').innerText = createdBy || '-';

    const statusEl = document.getElementById('detailStatus');
    if (status === 'verified') {
        statusEl.innerHTML = '<span class="status-badge status-sukses"><i class="ri-checkbox-circle-fill"></i> Sukses</span>';
    } else if (status === 'pending' || status === 'draft') {
        statusEl.innerHTML = '<span class="status-badge status-pending"><i class="ri-time-line"></i> Pending</span>';
    } else {
        statusEl.innerHTML = '<span class="status-badge status-gagal"><i class="ri-close-circle-fill"></i> Gagal</span>';
    }

    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.remove('hidden');
        lockBodyScroll();
    }
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        unlockBodyScroll();
    }
}

// --- Edit Harga Modal (Single) ---
function showEditHargaModal(id, nama, hargaLama) {
    // Close dropdowns
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));

    document.getElementById('editKomoditasId').value = id;
    document.getElementById('editNamaKomoditas').innerText = nama;
    document.getElementById('editHargaLama').innerText = hargaLama ? 'Rp' + Number(hargaLama).toLocaleString('id-ID') : '-';

    const hargaBaruInput = document.getElementById('harga_baru');
    const hargaBaruDisplay = document.getElementById('harga_baru_display');

    if (hargaBaruInput) hargaBaruInput.value = '';
    if (hargaBaruDisplay) hargaBaruDisplay.value = '';

    const modal = document.getElementById('editHargaModal');
    if (modal) {
        modal.classList.remove('hidden');
        lockBodyScroll();
    }
}

function closeEditHargaModal() {
    const modal = document.getElementById('editHargaModal');
    if (modal) {
        modal.classList.add('hidden');
        unlockBodyScroll();
    }
}

// --- Update All Modal (Bulk) ---
function showUpdateAllModal() {
    const tbody = document.getElementById('updateAllTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    const komoditasData = window.lurahKomoditasData || []; // Access global data

    komoditasData.forEach(item => {
        const hargaFormatted = item.harga ? 'Rp ' + Number(item.harga).toLocaleString('id-ID') : '-';
        const satuanText = item.satuan ? '/' + item.satuan : '';
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
        row.innerHTML = `
            <td class="py-2 px-2">
                <span class="font-medium text-gray-900 dark:text-white text-sm">${item.nama}</span>
            </td>
            <td class="py-2 px-2 text-left">
                <span class="text-gray-600 dark:text-gray-400 text-sm">${hargaFormatted}<span class="text-gray-400 text-xs">${satuanText}</span></span>
            </td>
            <td class="py-2 px-2">
                <div class="flex items-center">
                    <span class="text-gray-500 text-xs mr-1">Rp</span>
                    <input type="text" 
                        data-komoditas-id="${item.id}" 
                        data-harga-lama="${item.harga || ''}"
                        class="update-all-input w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-left text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="0"
                        oninput="formatRupiahInputBulk(this)">
                </div>
            </td>
            <td class="py-2 px-2">
                <input type="date" 
                    class="update-all-date w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500" />
            </td>
        `;
        tbody.appendChild(row);
    });

    const modal = document.getElementById('updateAllModal');
    if (modal) {
        modal.classList.remove('hidden');
        lockBodyScroll();
    }
}

function closeUpdateAllModal() {
    const modal = document.getElementById('updateAllModal');
    if (modal) {
        modal.classList.add('hidden');
        unlockBodyScroll();
    }
}


// ========================================
// Initialization & Event Listeners
// ========================================

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Config
    const configEl = document.getElementById('lurah-bapok-config');
    if (configEl) {
        LurahBapokConfig.init(configEl);
    }

    // 2. Search Functionality
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('#harga-bapok-table tbody tr');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // 3. Filter Functionality
    const filterStatus = document.getElementById('filter-status');
    const filterTanggal = document.getElementById('filter-tanggal');
    // const btnResetFilter is tricky, better explicitly select correct button
    const btnResetFilter = document.querySelector('.filter-section button'); // Assuming it's the only button or identify by inner text

    function applyFilters() {
        const statusValue = filterStatus?.value.toLowerCase() || '';
        const tanggalValue = filterTanggal?.value || '';
        let visibleCount = 0;

        tableRows.forEach(row => {
            if (row.classList.contains('no-data-row')) return; // Skip no-data row handling here

            const statusCell = row.querySelector('.status-badge');
            const tanggalCell = row.querySelector('.tanggal-cell');
            let showRow = true;

            // Date filtering needs to match text format "dd MMM yyyy" -> "yyyy-mm-dd"
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
                    // Format check - if needed
                    const rowTanggalISO = `${thn}-${bln}-${tgl.padStart(2, '0')}`;
                    if (rowTanggalISO !== tanggalValue) {
                        showRow = false;
                    }
                }
            }

            // Status filtering
            if (statusValue && statusCell && showRow) {
                let rowStatus = '';
                if (statusCell.classList.contains('status-sukses')) rowStatus = 'sukses';
                else if (statusCell.classList.contains('status-pending')) rowStatus = 'pending';
                else if (statusCell.classList.contains('status-gagal')) rowStatus = 'gagal';

                if (rowStatus !== statusValue) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        });

        // Toggle No Data Row
        let noDataRow = document.querySelector('.no-data-row');
        if (visibleCount === 0) {
            if (!noDataRow) {
                // Should inject row
            } else {
                noDataRow.style.display = '';
            }
        } else {
            if (noDataRow) noDataRow.style.display = 'none';
        }
    }

    if (filterStatus) filterStatus.addEventListener('change', applyFilters);
    if (filterTanggal) filterTanggal.addEventListener('change', applyFilters);
    if (btnResetFilter) {
        btnResetFilter.addEventListener('click', function () {
            if (filterStatus) filterStatus.value = '';
            if (filterTanggal) filterTanggal.value = '';
            applyFilters();
        });
    }

    // 4. Action Dropdown Functionality
    const actionDropdowns = document.querySelectorAll('.action-dropdown');
    actionDropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.action-dropdown-btn');
        const menu = dropdown.querySelector('.action-dropdown-menu'); // Corrected selector

        if (btn && menu) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close others
                actionDropdowns.forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });

                // Toggle current
                dropdown.classList.toggle('open');

                // Position logic...
                if (dropdown.classList.contains('open')) {
                    const rect = btn.getBoundingClientRect();
                    // Basic positioning
                    menu.style.top = (rect.bottom + 4) + 'px';
                    // Check if goes off right screen? Usually align right or left
                    // Implementation in original code aligned to right of button
                    menu.style.left = (rect.right - menu.offsetWidth) + 'px';

                    // Boundary check
                    const menuRect = menu.getBoundingClientRect();
                    if (menuRect.left < 8) {
                        menu.style.left = '8px';
                    }
                }
            });
        }
    });

    // Close dropdowns on click outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-dropdown')) {
            actionDropdowns.forEach(d => d.classList.remove('open'));
        }
    });

    // 5. Submit handlers

    // --- Update Single Price ---
    const btnSimpanHarga = document.getElementById('btnSimpanHarga');
    if (btnSimpanHarga) {
        btnSimpanHarga.addEventListener('click', function () {
            const form = document.getElementById('formEditHarga');
            const dataUrl = LurahBapokConfig.updateHargaUrl; // Use config URL

            const formData = new FormData();
            formData.append('_token', LurahBapokConfig.csrfToken);
            formData.append('komoditas_id', document.getElementById('editKomoditasId').value);
            formData.append('harga_baru', document.getElementById('harga_baru').value);
            formData.append('tanggal', document.getElementById('editTanggalHarga').value);

            if (!dataUrl) {
                alert('Konfigurasi URL update harga tidak ditemukan');
                return;
            }

            fetch(dataUrl, {
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

                    // Update DOM directly to reflect changes
                    const row = document.querySelector(`[data-komoditas-id="${res.komoditas_id}"]`);
                    if (row) {
                        const hargaCell = row.querySelector('.harga-cell');
                        if (hargaCell) {
                            const satuan = hargaCell.innerText.match(/\/(\w+)/)?.[1] || '';
                            hargaCell.innerText = 'Rp' + Number(res.harga_baru).toLocaleString('id-ID') + (satuan ? '/' + satuan : '');
                        }

                        const tanggalCell = row.querySelector('.tanggal-cell');
                        if (tanggalCell) tanggalCell.innerText = res.tanggal;

                        const statusCell = row.querySelector('td:nth-child(5)');
                        if (statusCell) {
                            statusCell.innerHTML = '<span class="status-badge status-pending">⏳ Pending</span>';
                        }
                    }

                    showToast();
                    closeEditHargaModal();
                })
                .catch(err => {
                    console.error(err);
                    alert('Error server');
                });
        });
    }

    // --- Update Bulk Price ---
    const btnSimpanSemuaHarga = document.getElementById('btnSimpanSemuaHarga');
    if (btnSimpanSemuaHarga) {
        btnSimpanSemuaHarga.addEventListener('click', async function () {
            const inputs = document.querySelectorAll('.update-all-input');
            const hargaList = [];
            const dataUrl = LurahBapokConfig.updateHargaUrl; // Reusing endpoint

            inputs.forEach(input => {
                const hargaBaru = input.dataset.rawValue || parseRupiah(input.value.trim());
                const komoditasId = input.dataset.komoditasId;
                const hargaLama = input.dataset.hargaLama;

                const tanggalInput = input.parentElement.parentElement.nextElementSibling.querySelector('input[type="date"]');
                const tanggalHarga = tanggalInput ? tanggalInput.value : null;

                if (hargaBaru && hargaBaru !== hargaLama) {
                    hargaList.push({
                        komoditas_id: parseInt(komoditasId),
                        harga_baru: parseFloat(hargaBaru),
                        tanggal: tanggalHarga
                    });
                }
            });

            if (hargaList.length === 0) {
                alert('Tidak ada harga yang diubah.');
                return;
            }

            // Loading state
            const btnText = document.getElementById('btnSimpanSemuaText');
            const btnLoading = document.getElementById('btnSimpanSemuaLoading');

            if (btnText) btnText.textContent = 'Menyimpan... (0/' + hargaList.length + ')';
            if (btnLoading) btnLoading.classList.remove('hidden');
            btnSimpanSemuaHarga.disabled = true;

            let successCount = 0;
            let failCount = 0;

            for (let i = 0; i < hargaList.length; i++) {
                const item = hargaList[i];
                if (btnText) btnText.textContent = `Menyimpan... (${i + 1}/${hargaList.length})`;

                try {
                    const formData = new FormData();
                    formData.append('_token', LurahBapokConfig.csrfToken);
                    formData.append('komoditas_id', item.komoditas_id);
                    formData.append('harga_baru', item.harga_baru);
                    if (item.tanggal) formData.append('tanggal', item.tanggal);

                    const res = await fetch(dataUrl, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success) {
                        successCount++;
                        // Update DOM
                        const row = document.querySelector(`[data-komoditas-id="${item.komoditas_id}"]`);
                        if (row) {
                            const hargaCell = row.querySelector('.harga-cell');
                            if (hargaCell) {
                                const satuan = hargaCell.innerText.match(/\/(\w+)/)?.[1] || '';
                                hargaCell.innerText = 'Rp' + Number(item.harga_baru).toLocaleString('id-ID') + (satuan ? '/' + satuan : '');
                            }
                            const tanggalCell = row.querySelector('.tanggal-cell');
                            if (tanggalCell) tanggalCell.innerText = data.tanggal;

                            const statusCell = row.querySelector('td:nth-child(5)');
                            if (statusCell) statusCell.innerHTML = '<span class="status-badge status-pending">⏳ Pending</span>';
                        }
                    } else {
                        failCount++;
                    }
                } catch (err) {
                    failCount++;
                    console.error(err);
                }
            }

            // Reset
            if (btnText) btnText.textContent = 'Simpan Semua';
            if (btnLoading) btnLoading.classList.add('hidden');
            btnSimpanSemuaHarga.disabled = false;

            if (failCount === 0) {
                showToast();
                closeUpdateAllModal();
            } else {
                alert(`${successCount} berhasil, ${failCount} gagal.`);
                if (successCount > 0) closeUpdateAllModal();
            }
        });
    }

    // Modal close on outside click
    ['detailModal', 'editHargaModal', 'updateAllModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    // Call respective close function based on ID
                    if (id === 'detailModal') closeDetailModal();
                    if (id === 'editHargaModal') closeEditHargaModal();
                    if (id === 'updateAllModal') closeUpdateAllModal();
                }
            });
        }
    });

});

// Expose functions globally for onclick handlers in HTML
window.showDetailModal = showDetailModal;
window.closeDetailModal = closeDetailModal;
window.showEditHargaModal = showEditHargaModal;
window.closeEditHargaModal = closeEditHargaModal;
window.showUpdateAllModal = showUpdateAllModal;
window.closeUpdateAllModal = closeUpdateAllModal;
window.formatRupiahInput = formatRupiahInput;
window.formatRupiahInputBulk = formatRupiahInputBulk;
