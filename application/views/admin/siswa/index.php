<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Data Siswa</h2>
    <div class="flex space-x-2">
        <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Siswa
        </button>
        <a href="<?php echo base_url('admin/siswa/import'); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-file-excel mr-2"></i>Import Excel
        </a>
        <a href="<?php echo base_url('admin/siswa/export'); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
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
            <form method="GET" action="<?php echo base_url('admin/siswa'); ?>" class="flex items-center">
                <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
                <input type="text" name="search" value="<?php echo $search; ?>" 
                       placeholder="Cari NIS, nama siswa..." 
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
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>RFID UID</th>
                    <th>No. HP Ortu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($siswa)): ?>
                    <?php 
                    $no = ($this->input->get('page') ? ($this->input->get('page') - 1) * $per_page : 0) + 1;
                    foreach ($siswa as $s): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $s->nis; ?></td>
                            <td><?php echo $s->nama; ?></td>
                            <td><?php echo $s->nama_kelas; ?></td>
                            <td><?php echo $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                            <td>
                                <?php if ($s->rfid_uid): ?>
                                    <span class="badge badge-success"><?php echo $s->rfid_uid; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-gray-200 text-gray-800">Belum terdaftar</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $s->telp_ortu ? $s->telp_ortu : '-'; ?></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($s)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/siswa/delete/'.$s->id); ?>" 
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
                            <?php echo $search ? 'Data tidak ditemukan' : 'Belum ada data siswa'; ?>
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
<div id="siswaModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Siswa</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="siswaForm" method="POST" action="<?php echo base_url('admin/siswa/create'); ?>">
                <input type="hidden" name="id" id="siswa-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nis" class="form-label">NIS <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="nis" name="nis" required>
                    </div>

                    <div>
                        <label for="nisn" class="form-label">NISN</label>
                        <input type="text" class="form-input" id="nisn" name="nisn">
                    </div>

                    <div class="md:col-span-2">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" id="nama" name="nama" required>
                    </div>

                    <div>
                        <label for="kelas_id" class="form-label">Kelas <span class="text-red-500">*</span></label>
                        <select class="form-input" id="kelas_id" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?php echo $k->id; ?>"><?php echo $k->nama_kelas; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select class="form-input" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-input" id="tempat_lahir" name="tempat_lahir">
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" id="tanggal_lahir" name="tanggal_lahir">
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-input" id="alamat" name="alamat" rows="2"></textarea>
                    </div>

                    <div>
                        <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                        <input type="text" class="form-input" id="nama_ortu" name="nama_ortu">
                    </div>

                    <div>
                        <label for="telp_ortu" class="form-label">No. HP Orang Tua</label>
                        <input type="text" class="form-input" id="telp_ortu" name="telp_ortu" 
                               placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="md:col-span-2">
                        <label for="rfid_uid" class="form-label">RFID UID</label>
                        <input type="text" class="form-input" id="rfid_uid" name="rfid_uid" 
                               placeholder="Masukkan UID kartu RFID">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika belum memiliki kartu RFID</p>
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
        const modal = document.getElementById('siswaModal');
        const form = document.getElementById('siswaForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Siswa';
            form.action = '<?php echo base_url('admin/siswa/create'); ?>';
            form.reset();
            document.getElementById('siswa-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('siswaModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('siswaModal');
        const form = document.getElementById('siswaForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Siswa';
        form.action = '<?php echo base_url('admin/siswa/update/'); ?>' + data.id;
        
        document.getElementById('siswa-id').value = data.id;
        document.getElementById('nis').value = data.nis;
        document.getElementById('nisn').value = data.nisn || '';
        document.getElementById('nama').value = data.nama;
        document.getElementById('kelas_id').value = data.kelas_id;
        document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
        document.getElementById('tempat_lahir').value = data.tempat_lahir || '';
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('nama_ortu').value = data.nama_ortu || '';
        document.getElementById('telp_ortu').value = data.telp_ortu || '';
        document.getElementById('rfid_uid').value = data.rfid_uid || '';
        
        modal.classList.remove('hidden');
    }

    document.getElementById('siswaModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
