/**
 * Harga Bapok Admin Page JavaScript
 * Separated for better maintainability
 */

// ========================================
// Rupiah Formatting Functions
// ========================================
function formatRupiah(angka) {
    if (!angka) return '';
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseRupiah(str) {
    if (!str) return '';
    return str.replace(/\./g, '');
}

function formatRupiahInputAdmin(input) {
    let value = input.value.replace(/\D/g, ''); // Hanya angka
    document.getElementById('editHargaBaru').value = value; // Simpan value asli ke hidden input
    input.value = formatRupiah(value);
}

// ========================================
// Configuration (set via data attributes)
// ========================================
const HargaBapokConfig = {
    csrfToken: '',
    updateHargaUrl: '',
    publishUrl: '',
    pasarId: null,
    pasarSlug: '',

    init(element) {
        this.csrfToken = element.dataset.csrfToken || '';
        this.updateHargaUrl = element.dataset.updateHargaUrl || '';
        this.publishUrl = element.dataset.publishUrl || '';
        this.getPendingItemsUrl = element.dataset.getPendingItemsUrl || '';
        this.storeKomoditasUrl = element.dataset.storeKomoditasUrl || '';
        this.pasarId = element.dataset.pasarId || null;
        this.pasarSlug = element.dataset.pasarSlug || '';
    }
};

// ========================================
// Modal Functions
// ========================================
function showEditHargaModal(id, nama, hargaLama) {
    document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('open'));

    document.getElementById('editKomoditasId').value = id;
    document.getElementById('editNamaKomoditas').innerText = nama;
    document.getElementById('editHargaLama').innerText = hargaLama ? 'Rp' + Number(hargaLama).toLocaleString('id-ID') : '-';
    document.getElementById('editHargaBaru').value = '';
    document.getElementById('editHargaBaruDisplay').value = '';
    document.getElementById('editHargaModal').classList.remove('hidden');
}

function closeEditHargaModal() {
    document.getElementById('editHargaModal').classList.add('hidden');
}

function showDetailModal(id, nama, harga, satuan, hargaAcuan, tanggal, status, createdBy) {
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

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// ========================================
// Emoji Picker Functions
// ========================================
function pickEmojiMain(emoji) {
    const input = document.getElementById('input-emoji-main');
    const preview = document.getElementById('emoji-preview-main');
    if (input) input.value = emoji;
    if (preview) preview.textContent = emoji;
}

function pickEmojiSecondary(emoji) {
    const input = document.getElementById('addIconEmoji');
    const preview = document.getElementById('emoji-preview-secondary');
    if (input) input.value = emoji;
    if (preview) preview.textContent = emoji;
}

function showToast() {
    const toast = document.getElementById('toastSuccess');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
}

// ========================================
// Action Dropdowns
// ========================================
function initActionDropdowns() {
    const actionDropdowns = document.querySelectorAll('.action-dropdown');
    actionDropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.action-dropdown-btn');
        const menu = dropdown.querySelector('.action-dropdown-menu');
        if (btn && menu) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close all other dropdowns
                actionDropdowns.forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });

                // Toggle current dropdown
                dropdown.classList.toggle('open');

                // Position the fixed menu below the button
                if (dropdown.classList.contains('open')) {
                    const rect = btn.getBoundingClientRect();
                    menu.style.top = (rect.bottom + 4) + 'px';
                    menu.style.left = (rect.right - menu.offsetWidth) + 'px';

                    // Ensure menu doesn't go off-screen left
                    const menuRect = menu.getBoundingClientRect();
                    if (menuRect.left < 8) {
                        menu.style.left = '8px';
                    }
                }
            });
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-dropdown')) {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
        }
    });

    // Close dropdowns on scroll to prevent misalignment
    document.addEventListener('scroll', function () {
        actionDropdowns.forEach(dropdown => dropdown.classList.remove('open'));
    }, true);
}

// ========================================
// Main Initialization
// ========================================
document.addEventListener('DOMContentLoaded', function () {
    // Initialize config from data attributes
    const configEl = document.getElementById('harga-bapok-config');
    if (configEl) {
        HargaBapokConfig.init(configEl);
    }

    // Search functionality
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

    // Filter Pasar - Redirect to URL with slug
    const filterPasar = document.getElementById('filter-pasar');
    if (filterPasar) {
        filterPasar.addEventListener('change', function (e) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const slug = selectedOption.dataset.slug;
            if (slug) {
                window.location.href = '/pasar/' + slug + '/harga-bapok';
            }
        });
    }

    // Filter Status - server-side filtering
    const filterStatus = document.getElementById('filter-status');
    if (filterStatus) {
        filterStatus.addEventListener('change', function (e) {
            const url = new URL(window.location.href);
            if (e.target.value) {
                url.searchParams.set('status', e.target.value);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    }

    // Filter Tanggal - server-side filtering
    const filterTanggal = document.getElementById('filter-tanggal');
    if (filterTanggal) {
        filterTanggal.addEventListener('change', function (e) {
            const url = new URL(window.location.href);
            if (e.target.value) {
                url.searchParams.set('tanggal', e.target.value);
            } else {
                url.searchParams.delete('tanggal');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    }

    // Reset Filter
    const btnResetFilter = document.getElementById('btn-reset-filter');
    if (btnResetFilter) {
        btnResetFilter.addEventListener('click', function () {
            const currentSlug = filterPasar?.dataset.currentSlug || 'bantul';
            window.location.href = '/pasar/' + currentSlug + '/harga-bapok';
        });
    }

    // Page length functionality
    const pageLength = document.getElementById('page-length');
    if (pageLength) {
        pageLength.addEventListener('change', function (e) {
            const perPage = e.target.value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    }

    // Initialize action dropdowns
    initActionDropdowns();

    // ========================================
    // Checkbox Selection Logic
    // ========================================
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectedCountEl = document.getElementById('selected-count');
    const btnConfirmAll = document.getElementById('btn-confirm-all');

    // Function to update selected count and button state
    function updateSelectedCount() {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedItems.length;

        if (selectedCountEl) {
            selectedCountEl.textContent = count;
        }

        if (btnConfirmAll) {
            if (count > 0) {
                btnConfirmAll.disabled = false;
                btnConfirmAll.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btnConfirmAll.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
            } else {
                btnConfirmAll.disabled = true;
                btnConfirmAll.classList.add('bg-gray-400', 'cursor-not-allowed');
                btnConfirmAll.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
            }
        }

        // Update select all checkbox state
        if (selectAllCheckbox) {
            const totalCheckboxes = itemCheckboxes.length;
            if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (count === totalCheckboxes) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    // Select All checkbox handler
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // Individual checkbox handlers
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Function to get selected komoditas IDs
    function getSelectedKomoditasIds() {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        return Array.from(checkedItems).map(cb => cb.value);
    }

    // ========================================
    // Confirm All Button (Updated for checkbox selection)
    // ========================================
    const modalConfirmHarga = document.getElementById('modalConfirmHarga');
    const btnCancelConfirm = document.getElementById('btn-cancel-confirm');
    const btnYesConfirm = document.getElementById('btn-yes-confirm');

    if (btnConfirmAll && modalConfirmHarga) {
        btnConfirmAll.addEventListener('click', function () {
            const selectedIds = getSelectedKomoditasIds();

            if (selectedIds.length === 0) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Pilih minimal satu komoditas untuk dikonfirmasi',
                    position: 'topRight',
                    timeout: 3000
                });
                return;
            }

            const container = document.getElementById('review-items-container');
            if (container) {
                container.innerHTML = '<div class="text-center text-gray-500 italic py-2">Memuat data...</div>';

                // Fetch pending items with selected IDs
                const url = new URL(HargaBapokConfig.getPendingItemsUrl, window.location.origin);
                url.searchParams.append('pasar_id', HargaBapokConfig.pasarId);
                url.searchParams.append('komoditas_ids', selectedIds.join(','));

                fetch(url)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data.length > 0) {
                            let html = '<ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">';
                            res.data.forEach(item => {
                                html += `<li class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-1">
                                <span>${item.nama_komoditas}</span>
                                <span class="font-bold text-emerald-600">${item.harga_formatted}</span>
                            </li>`;
                            });
                            html += '</ul>';
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<div class="text-center text-gray-500 italic py-2">Tidak ada perubahan data harga yang perlu dikonfirmasi.</div>';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        container.innerHTML = '<div class="text-center text-red-500 py-2">Gagal memuat data review.</div>';
                    });
            }
            modalConfirmHarga.classList.remove('hidden');
        });
    }

    if (btnCancelConfirm) {
        btnCancelConfirm.addEventListener('click', function () {
            modalConfirmHarga.classList.add('hidden');
        });
    }

    if (btnYesConfirm) {
        btnYesConfirm.addEventListener('click', function () {
            const selectedIds = getSelectedKomoditasIds();

            fetch(HargaBapokConfig.publishUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': HargaBapokConfig.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    confirm: true,
                    komoditas_ids: selectedIds
                })
            })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    modalConfirmHarga.classList.add('hidden');
                    if (status === 200 && body.success) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: body.message || 'Data harga berhasil dikonfirmasi!',
                            position: 'topRight',
                            timeout: 3000
                        });

                        // Update status only for selected items
                        selectedIds.forEach(id => {
                            const row = document.querySelector(`tr[data-komoditas-id="${id}"]`);
                            if (row) {
                                const statusBadge = row.querySelector('.status-badge');
                                if (statusBadge && statusBadge.classList.contains('status-pending')) {
                                    statusBadge.classList.remove('status-pending');
                                    statusBadge.classList.add('status-sukses');
                                    statusBadge.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Sukses';
                                }
                                // Uncheck the checkbox
                                const checkbox = row.querySelector('.item-checkbox');
                                if (checkbox) checkbox.checked = false;
                            }
                        });

                        // Reset select all and counter
                        updateSelectedCount();

                        // Reload halaman setelah 1.5 detik untuk refresh data
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        iziToast.error({ title: 'Gagal!', message: body.message || 'Gagal publish harga!', position: 'topRight', timeout: 3000 });
                    }
                })
                .catch((err) => {
                    modalConfirmHarga.classList.add('hidden');
                    console.error('Publish error:', err);
                    iziToast.error({
                        title: 'Gagal!',
                        message: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        position: 'topRight',
                        timeout: 4000
                    });
                });
        });
    }

    // ========================================
    // Save Harga Button
    // ========================================
    const btnSimpanHarga = document.getElementById('btnSimpanHarga');
    if (btnSimpanHarga) {
        btnSimpanHarga.addEventListener('click', function () {
            const komoditasId = document.getElementById('editKomoditasId').value;
            const hargaBaru = document.getElementById('editHargaBaru').value;

            if (!hargaBaru || hargaBaru <= 0) {
                alert('Masukkan harga yang valid');
                return;
            }

            const formData = new FormData();
            formData.append('_token', HargaBapokConfig.csrfToken);
            formData.append('komoditas_id', komoditasId);
            formData.append('harga_baru', hargaBaru);
            formData.append('pasar_id', HargaBapokConfig.pasarId);

            fetch(HargaBapokConfig.updateHargaUrl, {
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

                    const row = document.querySelector(`[data-komoditas-id="${res.komoditas_id}"]`);
                    if (row) {
                        const hargaCell = row.querySelectorAll('td')[4];
                        const tanggalCell = row.querySelectorAll('td')[3];
                        const statusCell = row.querySelector('.status-badge');

                        if (hargaCell) hargaCell.innerHTML = 'Rp' + Number(res.harga_baru).toLocaleString('id-ID');
                        if (tanggalCell) tanggalCell.innerText = res.updated_at;
                        if (statusCell) {
                            statusCell.className = 'status-badge status-pending';
                            statusCell.innerHTML = '<i class="ri-time-line"></i> Pending';
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

    // Close modals on overlay click
    const editHargaModal = document.getElementById('editHargaModal');
    if (editHargaModal) {
        editHargaModal.addEventListener('click', function (e) {
            if (e.target === this) closeEditHargaModal();
        });
    }

    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('click', function (e) {
            if (e.target === this) closeDetailModal();
        });
    }

    const btnAddData = document.getElementById('btn-add-data');
    const modalAddKomoditas = document.getElementById('modalAddKomoditas');
    const formAddKomoditas = document.getElementById('formAddKomoditas');
    const btnCloseAddModal = document.getElementById('btn-close-add-modal');
    const btnCancelAdd = document.getElementById('btn-cancel-add');

    // Image upload elements
    const inputImage = document.getElementById('input-image');
    const previewImage = document.getElementById('preview-image');
    const previewPlaceholderImage = document.getElementById('preview-placeholder-image');

    // Open modal
    if (btnAddData && modalAddKomoditas) {
        btnAddData.addEventListener('click', function () {
            // Reset form
            if (formAddKomoditas) formAddKomoditas.reset();
            // Reset emoji preview
            const emojiPreview = document.getElementById('emoji-preview-main');
            const emojiInput = document.getElementById('input-emoji-main');
            if (emojiPreview) emojiPreview.textContent = '🛒';
            if (emojiInput) emojiInput.value = '';
            // Reset image preview
            if (previewImage) {
                previewImage.src = '';
                previewImage.classList.add('hidden');
            }
            if (previewPlaceholderImage) previewPlaceholderImage.classList.remove('hidden');
            modalAddKomoditas.classList.remove('hidden');
        });
    }

    // Close modal functions
    function closeAddKomoditasModal() {
        if (modalAddKomoditas) modalAddKomoditas.classList.add('hidden');
    }

    if (btnCloseAddModal) {
        btnCloseAddModal.addEventListener('click', closeAddKomoditasModal);
    }
    if (btnCancelAdd) {
        btnCancelAdd.addEventListener('click', closeAddKomoditasModal);
    }
    if (modalAddKomoditas) {
        modalAddKomoditas.addEventListener('click', function (e) {
            if (e.target === this) closeAddKomoditasModal();
        });
    }

    // Image preview handler
    if (inputImage && previewImage && previewPlaceholderImage) {
        inputImage.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    previewPlaceholderImage.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submit
    if (formAddKomoditas) {
        formAddKomoditas.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_token', HargaBapokConfig.csrfToken);

            const submitBtn = document.getElementById('btn-submit-komoditas');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Menyimpan...';
            }

            fetch(HargaBapokConfig.storeKomoditasUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if (!res.success) {
                        iziToast.error({
                            title: 'Gagal!',
                            message: res.message || 'Gagal menyimpan komoditas',
                            position: 'topRight',
                            timeout: 4000
                        });
                        return;
                    }

                    iziToast.success({
                        title: 'Berhasil!',
                        message: 'Komoditas berhasil ditambahkan!',
                        position: 'topRight',
                        timeout: 3000
                    });

                    closeAddKomoditasModal();
                    setTimeout(() => window.location.reload(), 1500);
                })
                .catch(err => {
                    console.error(err);
                    iziToast.error({
                        title: 'Gagal!',
                        message: 'Terjadi kesalahan jaringan',
                        position: 'topRight',
                        timeout: 4000
                    });
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ri-save-line"></i> Simpan';
                    }
                });
        });
    }

    window.closeAddKomoditasModal = closeAddKomoditasModal;

    // ========================================
    // Export Excel (with Letterhead Header)
    // ========================================

    // Helper: convert image URL to base64
    function imageToBase64(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            // Only set crossOrigin if URL is not same-origin to avoid issues with local file/server
            if (url.startsWith('http') && !url.includes(window.location.origin)) {
                img.crossOrigin = 'anonymous';
            }

            img.onload = function () {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                try {
                    resolve(canvas.toDataURL('image/png'));
                } catch (e) {
                    // Canvas might be tainted if CORS headers are missing on the image response
                    console.warn('Canvas taint issue:', e);
                    resolve('');
                }
            };
            img.onerror = (e) => {
                console.warn('Image load error:', e);
                resolve(''); // fallback: no logo
            };
            img.src = url;

            // Make sure cached images trigger load
            if (img.complete || img.complete === undefined) {
                img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                img.src = url;
            }
        });
    }

    // Export Excel is now handled via direct link to server-side export

    // ========================================
    // Print Functionality
    // ========================================
    const btnPrint = document.getElementById('btn-print');
    if (btnPrint) {
        btnPrint.addEventListener('click', function () {
            const pasarName = configEl?.dataset.pasarName || 'Pasar';
            const today = new Date().toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const table = document.getElementById('harga-bapok-table');
            const rows = table.querySelectorAll('tbody tr');

            if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
                iziToast.warning({
                    title: 'Peringatan',
                    message: 'Tidak ada data untuk dicetak!',
                    position: 'topRight',
                    timeout: 3000
                });
                return;
            }

            let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Harga Bapok - ${pasarName}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { color: #059669; margin-bottom: 5px; }
                        .subtitle { color: #666; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                        th { background-color: #059669; color: white; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                        .status-sukses { color: #166534; background: #dcfce7; padding: 4px 8px; border-radius: 4px; }
                        .status-pending { color: #854d0e; background: #fef9c3; padding: 4px 8px; border-radius: 4px; }
                        .status-gagal { color: #991b1b; background: #fee2e2; padding: 4px 8px; border-radius: 4px; }
                        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <h1>📊 Daftar Harga Bahan Pokok</h1>
                    <p class="subtitle">${pasarName} - ${today}</p>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Komoditas</th>
                                <th>Tanggal</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            rows.forEach((row) => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    const no = cells[0].textContent.trim();
                    const komoditas = cells[1].textContent.trim();
                    const tanggal = cells[2].textContent.trim();
                    const harga = cells[3].textContent.trim();
                    const statusBadge = cells[4].querySelector('.status-badge');
                    const statusClass = statusBadge ? (
                        statusBadge.classList.contains('status-sukses') ? 'status-sukses' :
                            statusBadge.classList.contains('status-pending') ? 'status-pending' : 'status-gagal'
                    ) : '';
                    const statusText = statusBadge ? statusBadge.textContent.trim() : '-';
                    const createdBy = cells[5].textContent.trim();

                    printContent += `
                        <tr>
                            <td>${no}</td>
                            <td>${komoditas}</td>
                            <td>${tanggal}</td>
                            <td>${harga}</td>
                            <td><span class="${statusClass}">${statusText}</span></td>
                            <td>${createdBy}</td>
                        </tr>
                    `;
                }
            });

            printContent += `
                        </tbody>
                    </table>
                    <p class="footer">Dicetak pada ${today} - SIGAPAN Diskominfo</p>
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        });
    }

    // ========================================
    // Keyboard Shortcuts
    // ========================================
    document.addEventListener('keydown', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }

        // Ctrl + P = Print
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            document.getElementById('btn-print')?.click();
        }

        // Ctrl + E = Export Excel
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            document.getElementById('btn-export-excel')?.click();
        }

        // Ctrl + F = Focus Search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }

        // Escape = Close all modals
        if (e.key === 'Escape') {
            closeEditHargaModal();
            closeDetailModal();
            document.getElementById('modalConfirmHarga')?.classList.add('hidden');
        }

        // ? = Show shortcuts help
        if (e.key === '?') {
            iziToast.info({
                title: '⌨️ Keyboard Shortcuts',
                message: `
                    <div style="text-align: left; font-size: 13px;">
                        <b>Ctrl + P</b> = Print<br>
                        <b>Ctrl + E</b> = Export Excel<br>
                        <b>Ctrl + F</b> = Search<br>
                        <b>Esc</b> = Close Modal<br>
                        <b>?</b> = Show Shortcuts
                    </div>
                `,
                position: 'topRight',
                timeout: 5000,
                progressBar: false
            });
        }
    });

    // ========================================
    // Auto-refresh reminder (every 5 minutes)
    // ========================================
    let lastActivityTime = Date.now();

    document.addEventListener('mousemove', () => lastActivityTime = Date.now());
    document.addEventListener('keydown', () => lastActivityTime = Date.now());

    setInterval(() => {
        const inactiveTime = Date.now() - lastActivityTime;
        if (inactiveTime > 300000) {
            iziToast.info({
                title: 'Refresh Data?',
                message: 'Data mungkin sudah berubah. Klik untuk refresh.',
                position: 'bottomRight',
                timeout: 10000,
                buttons: [
                    ['<button>Refresh</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        window.location.reload();
                    }, true]
                ]
            });
            lastActivityTime = Date.now();
        }
    }, 60000);

    console.log('💡 Tip: Press ? to see keyboard shortcuts');
});
