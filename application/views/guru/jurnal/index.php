<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Isi Jurnal Mengajar</h2>
    <p class="text-gray-600 mt-1">Pilih jadwal untuk mengisi jurnal hari ini</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <?php if (!empty($jadwal_tersedia)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($jadwal_tersedia as $jadwal): ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <div class="mb-3">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo $jadwal->nama_mapel; ?></h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-users mr-1"></i><?php echo $jadwal->nama_kelas; ?>
                        </p>
                    </div>
                    
                    <div class="mb-3 flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        <span><?php echo substr($jadwal->jam_mulai, 0, 5); ?> - <?php echo substr($jadwal->jam_selesai, 0, 5); ?></span>
                    </div>
                    
                    <a href="<?php echo base_url('guru/jurnal/create/' . $jadwal->id); ?>" 
                       class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-pen mr-2"></i>Isi Jurnal
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-calendar-check text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Jadwal Tersedia</h3>
            <p class="text-gray-500">
                <?php if (date('N') == 7): ?>
                    Hari ini adalah hari Minggu, tidak ada jadwal mengajar.
                <?php else: ?>
                    Semua jurnal untuk hari ini sudah terisi atau tidak ada jadwal di hari ini.
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Info -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Pilih jadwal yang akan diisi jurnalnya</li>
                    <li>Isi materi pembelajaran dan absensi siswa (H/S/I/A)</li>
                    <li>Jurnal hanya bisa diisi sekali per jadwal per hari</li>
                    <li>Pastikan semua data sudah benar sebelum menyimpan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
