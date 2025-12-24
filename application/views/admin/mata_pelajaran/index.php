<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Data Mata Pelajaran</h2>
    <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Mata Pelajaran
    </button>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mata_pelajaran)): ?>
                    <?php $no = 1; foreach ($mata_pelajaran as $mp): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $mp->kode; ?></td>
                            <td><?php echo $mp->nama; ?></td>
                            <td><?php echo $mp->deskripsi ? $mp->deskripsi : '-'; ?></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($mp)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/mata_pelajaran/delete/'.$mp->id); ?>" 
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
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            Belum ada data mata pelajaran
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="mapelModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Mata Pelajaran</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="mapelForm" method="POST" action="<?php echo base_url('admin/mata_pelajaran/create'); ?>">
                <input type="hidden" name="id" id="mapel-id">
                
                <div class="mb-4">
                    <label for="kode" class="form-label">Kode Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" id="kode" name="kode" 
                           placeholder="Contoh: MTK, IPA, IPS" required>
                </div>

                <div class="mb-4">
                    <label for="nama" class="form-label">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" id="nama" name="nama" 
                           placeholder="Contoh: Matematika" required>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-input" id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Deskripsi mata pelajaran (opsional)"></textarea>
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
    function openModal(mode) {
        const modal = document.getElementById('mapelModal');
        const form = document.getElementById('mapelForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Mata Pelajaran';
            form.action = '<?php echo base_url('admin/mata_pelajaran/create'); ?>';
            form.reset();
            document.getElementById('mapel-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('mapelModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('mapelModal');
        const form = document.getElementById('mapelForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Mata Pelajaran';
        form.action = '<?php echo base_url('admin/mata_pelajaran/update/'); ?>' + data.id;
        
        document.getElementById('mapel-id').value = data.id;
        document.getElementById('kode').value = data.kode;
        document.getElementById('nama').value = data.nama;
        document.getElementById('deskripsi').value = data.deskripsi || '';
        
        modal.classList.remove('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('mapelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
