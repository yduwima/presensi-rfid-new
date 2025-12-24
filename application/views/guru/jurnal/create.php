<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Isi Jurnal Mengajar</h2>
    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
        <span><i class="fas fa-book mr-1"></i><?php echo $jadwal->nama_mapel; ?></span>
        <span><i class="fas fa-users mr-1"></i><?php echo $jadwal->nama_kelas; ?></span>
        <span><i class="fas fa-clock mr-1"></i><?php echo substr($jadwal->jam_mulai, 0, 5); ?> - <?php echo substr($jadwal->jam_selesai, 0, 5); ?></span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="<?php echo base_url('guru/jurnal/create'); ?>">
        <input type="hidden" name="jadwal_id" value="<?php echo $jadwal->id; ?>">
        
        <!-- Tanggal -->
        <div class="mb-6">
            <label for="tanggal" class="form-label">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" class="form-input" id="tanggal" name="tanggal" 
                   value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
            <p class="text-xs text-gray-500 mt-1">Tanggal pembelajaran</p>
        </div>

        <!-- Materi -->
        <div class="mb-6">
            <label for="materi" class="form-label">Materi Pembelajaran <span class="text-red-500">*</span></label>
            <textarea class="form-input" id="materi" name="materi" rows="4" required
                      placeholder="Tuliskan materi yang diajarkan..."></textarea>
        </div>

        <!-- Keterangan -->
        <div class="mb-6">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-input" id="keterangan" name="keterangan" rows="2"
                      placeholder="Catatan tambahan (opsional)"></textarea>
        </div>

        <!-- Absensi Siswa -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                Absensi Siswa
            </h3>
            
            <?php if (!empty($siswa)): ?>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no = 1; foreach ($siswa as $s): ?>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo $no++; ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo $s->nis; ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo $s->nama; ?></td>
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="siswa_id[]" value="<?php echo $s->id; ?>">
                                        <div class="flex justify-center space-x-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="status[<?php echo $s->id; ?>]" value="H" 
                                                       class="form-radio h-4 w-4 text-green-600" checked>
                                                <span class="ml-1 text-sm text-gray-700">H</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="status[<?php echo $s->id; ?>]" value="S" 
                                                       class="form-radio h-4 w-4 text-yellow-600">
                                                <span class="ml-1 text-sm text-gray-700">S</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="status[<?php echo $s->id; ?>]" value="I" 
                                                       class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-1 text-sm text-gray-700">I</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="status[<?php echo $s->id; ?>]" value="A" 
                                                       class="form-radio h-4 w-4 text-red-600">
                                                <span class="ml-1 text-sm text-gray-700">A</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-600">
                        <strong>Keterangan:</strong>
                        <span class="ml-2"><span class="text-green-600 font-medium">H</span> = Hadir</span>
                        <span class="ml-2"><span class="text-yellow-600 font-medium">S</span> = Sakit</span>
                        <span class="ml-2"><span class="text-blue-600 font-medium">I</span> = Izin</span>
                        <span class="ml-2"><span class="text-red-600 font-medium">A</span> = Alpha</span>
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-users-slash text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500">Tidak ada siswa di kelas ini</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2">
            <a href="<?php echo base_url('guru/jurnal'); ?>" 
               class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Jurnal
            </button>
        </div>
    </form>
</div>

<script>
    // Quick select all as Hadir
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        // Add quick action buttons
        const tableHead = document.querySelector('thead tr');
        if (tableHead) {
            const quickActionsHTML = `
                <div class="mb-3 flex justify-end space-x-2">
                    <button type="button" onclick="setAllStatus('H')" class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">
                        Semua Hadir
                    </button>
                    <button type="button" onclick="setAllStatus('A')" class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">
                        Semua Alpha
                    </button>
                </div>
            `;
            document.querySelector('.border.border-gray-200.rounded-lg').insertAdjacentHTML('beforebegin', quickActionsHTML);
        }
    });

    function setAllStatus(status) {
        const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
</script>
