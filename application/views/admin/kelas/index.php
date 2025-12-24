<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Data Kelas</h2>
    <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Kelas
    </button>
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
            <form method="GET" action="<?php echo base_url('admin/kelas'); ?>" class="flex items-center">
                <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
                <input type="text" name="search" value="<?php echo $search; ?>" 
                       placeholder="Cari nama kelas, tahun ajaran..." 
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
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kelas)): ?>
                    <?php 
                    $no = ($this->input->get('page') ? ($this->input->get('page') - 1) * $per_page : 0) + 1;
                    foreach ($kelas as $k): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $k->nama_kelas; ?></td>
                            <td>Kelas <?php echo $k->tingkat; ?></td>
                            <td><?php echo $k->tahun; ?></td>
                            <td><?php echo $k->wali_kelas_nama ? $k->wali_kelas_nama : '-'; ?></td>
                            <td>
                                <?php 
                                $this->load->model('Kelas_model');
                                $jumlah = $this->Kelas_model->count_siswa($k->id);
                                echo $jumlah . ' siswa';
                                ?>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($k)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/kelas/delete/'.$k->id); ?>" 
                                       class="text-red-600 hover:text-red-800"
                                       onclick="return confirmDelete()">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <?php echo $search ? 'Tidak ada data yang sesuai dengan pencarian' : 'Belum ada data kelas'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (!empty($pagination)): ?>
        <div class="flex justify-center mt-6">
            <?php echo $pagination; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="kelasModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Kelas</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="kelasForm" method="POST" action="<?php echo base_url('admin/kelas/create'); ?>">
                <input type="hidden" name="id" id="kelas-id">
                
                <div class="mb-4">
                    <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" id="nama_kelas" name="nama_kelas" 
                           placeholder="Contoh: X IPA 1" required>
                </div>

                <div class="mb-4">
                    <label for="tingkat" class="form-label">Tingkat <span class="text-red-500">*</span></label>
                    <select class="form-input" id="tingkat" name="tingkat" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select class="form-input" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?php echo $ta->id; ?>"><?php echo $ta->tahun; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
                    <select class="form-input" id="wali_kelas_id" name="wali_kelas_id">
                        <option value="">-- Pilih Wali Kelas --</option>
                        <?php foreach ($guru as $g): ?>
                            <option value="<?php echo $g->id; ?>"><?php echo $g->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
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
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    }
    
    function openModal(mode) {
        const modal = document.getElementById('kelasModal');
        const form = document.getElementById('kelasForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Kelas';
            form.action = '<?php echo base_url('admin/kelas/create'); ?>';
            form.reset();
            document.getElementById('kelas-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('kelasModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('kelasModal');
        const form = document.getElementById('kelasForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Kelas';
        form.action = '<?php echo base_url('admin/kelas/update/'); ?>' + data.id;
        
        document.getElementById('kelas-id').value = data.id;
        document.getElementById('nama_kelas').value = data.nama_kelas;
        document.getElementById('tingkat').value = data.tingkat;
        document.getElementById('tahun_ajaran_id').value = data.tahun_ajaran_id;
        document.getElementById('wali_kelas_id').value = data.wali_kelas_id || '';
        
        modal.classList.remove('hidden');
    }
    
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus kelas ini?');
    }

    // Close modal when clicking outside
    document.getElementById('kelasModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
