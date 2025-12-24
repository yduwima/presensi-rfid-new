<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Data Guru dan Staff</h2>
    <div class="flex space-x-2">
        <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Guru
        </button>
        <a href="<?php echo base_url('admin/guru/import'); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-file-excel mr-2"></i>Import Excel
        </a>
        <a href="<?php echo base_url('admin/guru/export'); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-download mr-2"></i>Export
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Search and Filter -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-600">Show</label>
            <select onchange="changePerPage(this.value)" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="20" <?php echo $per_page == 20 ? 'selected' : ''; ?>>20</option>
                <option value="30" <?php echo $per_page == 30 ? 'selected' : ''; ?>>30</option>
                <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            <label class="text-sm text-gray-600">entries</label>
        </div>
        
        <div>
            <form method="GET" action="<?php echo base_url('admin/guru'); ?>" class="flex items-center">
                <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
                <input type="text" name="search" value="<?php echo $search; ?>" 
                       placeholder="Cari NIP, nama guru..." 
                       class="px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>RFID UID</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($guru)): ?>
                    <?php 
                    $no = ($this->input->get('page') ? ($this->input->get('page') - 1) * $per_page : 0) + 1;
                    foreach ($guru as $g): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $g->nip; ?></td>
                            <td><?php echo $g->nama; ?></td>
                            <td><?php echo $g->email ? $g->email : '-'; ?></td>
                            <td><?php echo $g->telp ? $g->telp : '-'; ?></td>
                            <td>
                                <?php if ($g->rfid_uid): ?>
                                    <span class="badge badge-success"><?php echo $g->rfid_uid; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-gray-200 text-gray-800">Belum terdaftar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <?php if ($g->is_wali_kelas): ?>
                                        <span class="badge badge-info">Wali Kelas</span>
                                    <?php endif; ?>
                                    <?php if ($g->is_piket): ?>
                                        <span class="badge badge-warning">Piket</span>
                                    <?php endif; ?>
                                    <?php if ($g->is_bk): ?>
                                        <span class="badge bg-purple-100 text-purple-800">BK</span>
                                    <?php endif; ?>
                                    <?php if (!$g->is_wali_kelas && !$g->is_piket && !$g->is_bk): ?>
                                        <span class="badge bg-gray-200 text-gray-800">Guru</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($g)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmResetPassword(<?php echo $g->id; ?>, '<?php echo htmlspecialchars($g->nama); ?>')" 
                                            class="text-yellow-600 hover:text-yellow-800" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/guru/delete/'.$g->id); ?>" 
                                       class="text-red-600 hover:text-red-800"
                                       onclick="return confirmDelete()" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <?php echo $search ? 'Data tidak ditemukan' : 'Belum ada data guru'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination): ?>
        <div class="mt-4 flex justify-center">
            <?php echo $pagination; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="guruModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Guru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="guruForm" method="POST" action="<?php echo base_url('admin/guru/create'); ?>">
                <input type="hidden" name="id" id="guru-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nip" class="form-label">NIP <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="nip" name="nip" required>
                    </div>

                    <div>
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="nama" name="nama" required>
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-input" id="email" name="email" 
                               placeholder="guru@example.com">
                    </div>

                    <div>
                        <label for="telp" class="form-label">No. HP</label>
                        <input type="text" class="form-input" id="telp" name="telp" 
                               placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-input" id="alamat" name="alamat" rows="2"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="rfid_uid" class="form-label">RFID UID</label>
                        <input type="text" class="form-input" id="rfid_uid" name="rfid_uid" 
                               placeholder="Masukkan UID kartu RFID">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika belum memiliki kartu RFID</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Role/Tugas Tambahan</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_wali_kelas" id="is_wali_kelas" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Wali Kelas</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_piket" id="is_piket" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Guru Piket</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_bk" id="is_bk" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Bimbingan Konseling (BK)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function openModal(mode) {
        const modal = document.getElementById('guruModal');
        const form = document.getElementById('guruForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Guru';
            form.action = '<?php echo base_url('admin/guru/create'); ?>';
            form.reset();
            document.getElementById('guru-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('guruModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('guruModal');
        const form = document.getElementById('guruForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Guru';
        form.action = '<?php echo base_url('admin/guru/update/'); ?>' + data.id;
        
        document.getElementById('guru-id').value = data.id;
        document.getElementById('nip').value = data.nip;
        document.getElementById('nama').value = data.nama;
        document.getElementById('email').value = data.email || '';
        document.getElementById('telp').value = data.telp || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('rfid_uid').value = data.rfid_uid || '';
        document.getElementById('is_wali_kelas').checked = data.is_wali_kelas == 1;
        document.getElementById('is_piket').checked = data.is_piket == 1;
        document.getElementById('is_bk').checked = data.is_bk == 1;
        
        modal.classList.remove('hidden');
    }

    document.getElementById('guruModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus data guru ini? Akun user terkait juga akan dihapus.');
    }

    function confirmResetPassword(id, nama) {
        if (confirm('Reset password untuk ' + nama + '?\nPassword akan direset ke NIP guru.')) {
            window.location.href = '<?php echo base_url('admin/guru/reset_password/'); ?>' + id;
        }
    }
</script>
