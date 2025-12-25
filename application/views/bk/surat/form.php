<div class="mb-4">
    <a href="<?php echo base_url('bk/surat'); ?>" class="text-purple-600 hover:text-purple-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-6"><?php echo isset($surat) ? 'Edit Surat Panggilan' : 'Buat Surat Panggilan'; ?></h3>
    
    <form action="<?php echo isset($surat) ? base_url('bk/surat/update/' . $surat->id) : base_url('bk/surat/store'); ?>" method="post">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Siswa *</label>
                <select name="siswa_id" class="w-full border border-gray-300 rounded px-4 py-2" required <?php echo isset($surat) ? 'disabled' : ''; ?>>
                    <option value="">Pilih Siswa</option>
                    <?php foreach ($all_siswa as $s): ?>
                    <option value="<?php echo $s->id; ?>" 
                        <?php echo (isset($siswa) && $siswa->id == $s->id) || (isset($surat) && $surat->siswa_id == $s->id) ? 'selected' : ''; ?>>
                        <?php echo $s->nis . ' - ' . $s->nama . ' (' . $s->nama_kelas . ')'; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat *</label>
                <input type="text" name="nomor_surat" class="w-full border border-gray-300 rounded px-4 py-2" 
                    value="<?php echo isset($surat) ? $surat->nomor_surat : ''; ?>" placeholder="001/BK/SMA/XI/2024" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari *</label>
                <input type="text" name="hari" class="w-full border border-gray-300 rounded px-4 py-2" 
                    value="<?php 
                    if (isset($surat)) {
                        $days = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
                        echo $days[date('l', strtotime($surat->waktu_panggilan))];
                    }
                    ?>" placeholder="Senin" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                <input type="date" name="tanggal" class="w-full border border-gray-300 rounded px-4 py-2" 
                    value="<?php echo isset($surat) ? date('Y-m-d', strtotime($surat->tanggal_surat)) : date('Y-m-d'); ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu *</label>
                <input type="time" name="waktu" class="w-full border border-gray-300 rounded px-4 py-2" 
                    value="<?php echo isset($surat) ? date('H:i', strtotime($surat->waktu_panggilan)) : ''; ?>" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat</label>
                <input type="text" name="tempat" class="w-full border border-gray-300 rounded px-4 py-2" 
                    value="Ruang BK" placeholder="Ruang BK" readonly>
                <p class="text-xs text-gray-500 mt-1">Lokasi pertemuan (default: Ruang BK)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Masalah *</label>
                <select name="jenis" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    <option value="alpha">Alpha/Tidak Masuk</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Kategori masalah kedisiplinan</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Masalah *</label>
                <textarea name="keterangan" rows="4" class="w-full border border-gray-300 rounded px-4 py-2" 
                    placeholder="Uraikan masalah kedisiplinan siswa..." required><?php echo isset($surat) ? $surat->alasan : ''; ?></textarea>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                <i class="fas fa-save mr-2"></i><?php echo isset($surat) ? 'Update Surat' : 'Simpan Surat'; ?>
            </button>
            <a href="<?php echo base_url('bk/surat'); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>
