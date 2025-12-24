<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-users-class mr-2"></i>Wali Kelas - Input Izin Siswa
        </h1>
        <p class="text-gray-600">Kelola izin sakit dan izin siswa di kelas <?= $kelas->nama_kelas ?></p>
    </div>

    <?php if($this->session->flashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
        <i class="fas fa-check-circle mr-2"></i><?= $this->session->flashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
        <i class="fas fa-exclamation-circle mr-2"></i><?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Add Izin Button -->
    <div class="mb-6">
        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Tambah Izin Siswa
        </button>
    </div>

    <!-- Recent Izin Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-list mr-2"></i>Daftar Izin Siswa (30 Terakhir)
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($izin_recent)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            Belum ada data izin siswa
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($izin_recent as $index => $izin): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $izin->nis ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $izin->siswa_nama ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($izin->jenis == 'sakit'): ?>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-thermometer-half mr-1"></i>Sakit
                            </span>
                            <?php else: ?>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-file-alt mr-1"></i>Izin
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= date('d M Y', strtotime($izin->tanggal_mulai)) ?> - <?= date('d M Y', strtotime($izin->tanggal_selesai)) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?= character_limiter($izin->keterangan, 50) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?= base_url('guru/wali_kelas/delete/'.$izin->id) ?>" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')"
                               class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Izin -->
<div id="izinModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Izin Siswa
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="<?= base_url('guru/wali_kelas/create') ?>" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="space-y-4">
                <!-- Siswa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="siswa_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach($siswa as $s): ?>
                        <option value="<?= $s->id ?>"><?= $s->nis ?> - <?= $s->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jenis Izin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Izin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan" rows="3" required 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Alasan izin/sakit..."></textarea>
                </div>

                <!-- Bukti File -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti (Surat/Foto) <span class="text-gray-500 text-xs">(Opsional, max 2MB)</span>
                    </label>
                    <input type="file" name="bukti_file" accept=".jpg,.jpeg,.png,.pdf" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, PDF</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal()" 
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('izinModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('izinModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('izinModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
