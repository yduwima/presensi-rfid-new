<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Data Semester</h2>
    <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Semester
    </button>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($semester)): ?>
                    <?php $no = 1; foreach ($semester as $s): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $s->nama; ?></td>
                            <td><?php echo $s->tahun; ?></td>
                            <td>
                                <?php if ($s->is_active): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-gray-200 text-gray-800">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <?php if (!$s->is_active): ?>
                                        <a href="<?php echo base_url('admin/semester/set_active/'.$s->id); ?>" 
                                           class="text-green-600 hover:text-green-800"
                                           onclick="return confirm('Set sebagai semester aktif?')">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($s)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/semester/delete/'.$s->id); ?>" 
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
                            Belum ada data semester
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="semesterModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Semester</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="semesterForm" method="POST" action="<?php echo base_url('admin/semester/create'); ?>">
                <input type="hidden" name="id" id="semester-id">
                
                <div class="mb-4">
                    <label for="nama" class="form-label">Nama Semester <span class="text-red-500">*</span></label>
                    <select class="form-input" id="nama" name="nama" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
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
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Set sebagai semester aktif</span>
                    </label>
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
        const modal = document.getElementById('semesterModal');
        const form = document.getElementById('semesterForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Semester';
            form.action = '<?php echo base_url('admin/semester/create'); ?>';
            form.reset();
            document.getElementById('semester-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('semesterModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('semesterModal');
        const form = document.getElementById('semesterForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Semester';
        form.action = '<?php echo base_url('admin/semester/update/'); ?>' + data.id;
        
        document.getElementById('semester-id').value = data.id;
        document.getElementById('nama').value = data.nama;
        document.getElementById('tahun_ajaran_id').value = data.tahun_ajaran_id;
        document.getElementById('is_active').checked = data.is_active == 1;
        
        modal.classList.remove('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('semesterModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
