/**
 * Lurah Komoditas Page JavaScript
 * External JS file for lurah.blade.php
 */

// ========================================
// Configuration
// ========================================
const LurahConfig = {
    csrfToken: '',
    updateHargaUrl: '',
    pasarId: null,

    init(element) {
        this.csrfToken = element.dataset.csrfToken || '';
        this.updateHargaUrl = element.dataset.updateHargaUrl || '';
        this.pasarId = element.dataset.pasarId || null;
    }
};

// ========================================
// Toast Notification
// ========================================
function showToast() {
    const toast = document.getElementById('toastSuccess');
    if (toast) {
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    }
}

// ========================================
// Body Scroll Lock (for modals)
// ========================================
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
// Detail Modal Functions
// ========================================
function showDetailModal(id, nama, harga, satuan, hargaAcuan, tanggal, status, createdBy) {
    // Close all dropdowns
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));

    const modal = document.getElementById('detailModal');
    if (!modal) return;

    // Set modal content
    const elNama = document.getElementById('modalNama');
    const elHarga = document.getElementById('modalHarga');
    const elEmoji = document.getElementById('modalEmoji');

    if (elNama) elNama.innerText = nama || '-';
    if (elHarga) {
        elHarga.innerText = harga ? 'Rp' + Number(harga).toLocaleString('id-ID') + (satuan ? '/' + satuan : '') : '-';
    }
    if (elEmoji) elEmoji.innerText = getKomoditasIcon(nama);

    modal.classList.remove('hidden');
    lockBodyScroll();
}

function closeModal() {
    const modal = document.getElementById('detailModal');
    if (modal) modal.classList.add('hidden');
    unlockBodyScroll();
}

// ========================================
// Edit Harga Modal Functions
// ========================================
function showEditHargaModal(id, nama, hargaLama) {
    // Close all dropdowns
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));

    document.getElementById('editKomoditasId').value = id;
    document.getElementById('editNamaKomoditas').innerText = nama;
    document.getElementById('editHargaLama').innerText =
        hargaLama ? 'Rp' + Number(hargaLama).toLocaleString('id-ID') : '-';

    document.getElementById('harga_baru').value = '';
    document.getElementById('editHargaModal').classList.remove('hidden');
    lockBodyScroll();
}

function closeEditHargaModal() {
    document.getElementById('editHargaModal').classList.add('hidden');
    unlockBodyScroll();
}

// ========================================
// Komoditas Icon Mapping
// ========================================
const komoditasIcons = {
    'beras': '🍚',
    'cabai': '🌶️',
    'bawang': '🧅',
    'ayam': '🍗',
    'telur': '🥚',
    'minyak': '🛢️',
    'gula': '🍬',
    'daging': '🥩',
};

function getKomoditasIcon(nama) {
    nama = (nama || '').toLowerCase();
    if (nama.includes('beras')) return komoditasIcons['beras'];
    if (nama.includes('cabai')) return komoditasIcons['cabai'];
    if (nama.includes('bawang')) return komoditasIcons['bawang'];
    if (nama.includes('ayam')) return komoditasIcons['ayam'];
    if (nama.includes('telur')) return komoditasIcons['telur'];
    if (nama.includes('minyak')) return komoditasIcons['minyak'];
    if (nama.includes('gula')) return komoditasIcons['gula'];
    if (nama.includes('daging')) return komoditasIcons['daging'];
    return '🛒';
}

// ========================================
// Main Initialization
// ========================================
document.addEventListener('DOMContentLoaded', function () {
    // Initialize config
    const configEl = document.getElementById('lurah-config');
    if (configEl) {
        LurahConfig.init(configEl);
    }

    // ========================================
    // Search Functionality
    // ========================================
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('#harga-bapok-table tbody tr:not(.no-data-row)');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // ========================================
    // Filter Functionality (Client-side)
    // ========================================
    const filterStatus = document.getElementById('filter-status');
    const filterTanggal = document.getElementById('filter-tanggal');
    const btnResetFilter = document.querySelector('button i.ri-refresh-line')?.parentElement;

    function applyFilters() {
        const statusValue = filterStatus?.value.toLowerCase() || '';
        const tanggalValue = filterTanggal?.value || '';
        let visibleCount = 0;

        tableRows.forEach(row => {
            const statusCell = row.querySelector('.status-badge');
            const tanggalCell = row.querySelector('.tanggal-cell');
            let showRow = true;

            // Status filter
            if (statusValue && statusCell) {
                let rowStatus = '';
                if (statusCell.classList.contains('status-sukses')) rowStatus = 'sukses';
                else if (statusCell.classList.contains('status-pending')) rowStatus = 'pending';
                else if (statusCell.classList.contains('status-gagal')) rowStatus = 'gagal';
                if (rowStatus !== statusValue) {
                    showRow = false;
                }
            }

            // Date filter
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
                    if (bln.length === 3 && !bulanMap[bln]) {
                        bln = ('0' + (new Date(Date.parse(bln + " 1, 2000")).getMonth() + 1)).slice(-2);
                    }
                    const rowTanggalISO = `${thn}-${bln}-${tgl.padStart(2, '0')}`;
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

        // Show/hide no data row
        let noDataRow = document.querySelector('.no-data-row');
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', applyFilters);
    }
    if (filterTanggal) {
        filterTanggal.addEventListener('change', applyFilters);
    }
    if (btnResetFilter) {
        btnResetFilter.addEventListener('click', function () {
            if (filterStatus) filterStatus.value = '';
            if (filterTanggal) filterTanggal.value = '';
            applyFilters();
        });
    }

    // ========================================
    // Action Dropdown Functionality
    // ========================================
    const actionDropdowns = document.querySelectorAll('.action-dropdown');

    actionDropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.action-dropdown-btn');
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close all other dropdowns first
                actionDropdowns.forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                // Toggle current dropdown
                dropdown.classList.toggle('open');
            });
        }

        // Prevent dropdown items from closing dropdown prematurely
        const items = dropdown.querySelectorAll('.action-dropdown-item');
        items.forEach(item => {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-dropdown')) {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });

    // Close dropdowns on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
            closeEditHargaModal();
            closeModal();
        }
    });

    // ========================================
    // Save Harga Button Handler
    // ========================================
    const btnSimpanHarga = document.getElementById('btnSimpanHarga');
    if (btnSimpanHarga) {
        btnSimpanHarga.addEventListener('click', function () {
            const komoditasId = document.getElementById('editKomoditasId').value;
            const hargaBaru = document.getElementById('harga_baru').value;

            if (!hargaBaru || hargaBaru <= 0) {
                alert('Masukkan harga yang valid');
                return;
            }

            const formData = new FormData();
            formData.append('_token', LurahConfig.csrfToken);
            formData.append('komoditas_id', komoditasId);
            formData.append('harga_baru', hargaBaru);

            fetch(LurahConfig.updateHargaUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if (!res.success) {
                        alert('Gagal update: ' + (res.message || 'Unknown error'));
                        return;
                    }

                    // Update table row
                    const row = document.querySelector(`[data-komoditas-id="${res.komoditas_id}"]`);
                    if (row) {
                        const hargaCell = row.querySelector('.harga-cell');
                        const tanggalCell = row.querySelector('.tanggal-cell');
                        const statusCell = row.querySelector('.status-badge');

                        if (hargaCell) hargaCell.innerText = 'Rp' + Number(res.harga_baru).toLocaleString('id-ID');
                        if (tanggalCell) tanggalCell.innerText = res.tanggal;
                        if (statusCell) {
                            statusCell.className = 'status-badge status-pending';
                            statusCell.innerHTML = '⏳ Pending';
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

    // ========================================
    // Close modals on overlay click
    // ========================================
    const editHargaModal = document.getElementById('editHargaModal');
    if (editHargaModal) {
        editHargaModal.addEventListener('click', function (e) {
            if (e.target === this) closeEditHargaModal();
        });
    }

    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    }

    console.log('Lurah Komoditas JS loaded');
});
