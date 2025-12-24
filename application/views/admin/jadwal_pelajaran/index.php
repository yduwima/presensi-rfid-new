<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Jadwal Pelajaran</h2>
    <div class="flex space-x-2">
        <button onclick="openModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Jadwal
        </button>
    </div>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="<?php echo base_url('admin/jadwal_pelajaran'); ?>" class="flex items-center space-x-4">
        <div class="flex-1">
            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-input" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?php echo $k->id; ?>" <?php echo $kelas_filter == $k->id ? 'selected' : ''; ?>>
                        <?php echo $k->nama_kelas; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($kelas_filter): ?>
            <div class="pt-6">
                <a href="<?php echo base_url('admin/jadwal_pelajaran'); ?>" 
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Reset Filter
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jadwal)): ?>
                    <?php $no = 1; foreach ($jadwal as $j): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td class="font-medium"><?php echo $j->hari; ?></td>
                            <td><?php echo substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5); ?></td>
                            <td><?php echo $j->nama_kelas; ?></td>
                            <td><?php echo $j->nama_mapel; ?></td>
                            <td><?php echo $j->nama_guru; ?></td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="editData(<?php echo htmlspecialchars(json_encode($j)); ?>)" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/jadwal_pelajaran/delete/'.$j->id); ?>" 
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
                            <?php echo $kelas_filter ? 'Belum ada jadwal untuk kelas ini' : 'Belum ada data jadwal pelajaran'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="jadwalModal" class="modal-backdrop hidden">
    <div class="modal bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Tambah Jadwal</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="jadwalForm" method="POST" action="<?php echo base_url('admin/jadwal_pelajaran/create'); ?>">
                <input type="hidden" name="id" id="jadwal-id">
                
                <div class="mb-4">
                    <label for="kelas_id" class="form-label">Kelas <span class="text-red-500">*</span></label>
                    <select class="form-input" id="form_kelas_id" name="kelas_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?php echo $k->id; ?>"><?php echo $k->nama_kelas; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select class="form-input" id="mata_pelajaran_id" name="mata_pelajaran_id" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php foreach ($mata_pelajaran as $mp): ?>
                            <option value="<?php echo $mp->id; ?>"><?php echo $mp->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="guru_id" class="form-label">Guru <span class="text-red-500">*</span></label>
                    <select class="form-input" id="guru_id" name="guru_id" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru as $g): ?>
                            <option value="<?php echo $g->id; ?>"><?php echo $g->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="hari" class="form-label">Hari <span class="text-red-500">*</span></label>
                    <select class="form-input" id="hari" name="hari" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" class="form-input" id="jam_mulai" name="jam_mulai" required>
                    </div>
                    <div>
                        <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" class="form-input" id="jam_selesai" name="jam_selesai" required>
                    </div>
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
        const modal = document.getElementById('jadwalModal');
        const form = document.getElementById('jadwalForm');
        const title = document.getElementById('modalTitle');
        
        if (mode === 'add') {
            title.textContent = 'Tambah Jadwal';
            form.action = '<?php echo base_url('admin/jadwal_pelajaran/create'); ?>';
            form.reset();
            document.getElementById('jadwal-id').value = '';
        }
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('jadwalModal').classList.add('hidden');
    }

    function editData(data) {
        const modal = document.getElementById('jadwalModal');
        const form = document.getElementById('jadwalForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = 'Edit Jadwal';
        form.action = '<?php echo base_url('admin/jadwal_pelajaran/update/'); ?>' + data.id;
        
        document.getElementById('jadwal-id').value = data.id;
        document.getElementById('form_kelas_id').value = data.kelas_id;
        document.getElementById('mata_pelajaran_id').value = data.mata_pelajaran_id;
        document.getElementById('guru_id').value = data.guru_id;
        document.getElementById('hari').value = data.hari;
        document.getElementById('jam_mulai').value = data.jam_mulai;
        document.getElementById('jam_selesai').value = data.jam_selesai;
        
        modal.classList.remove('hidden');
    }

    document.getElementById('jadwalModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
