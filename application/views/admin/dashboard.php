<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Siswa Card -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($total_siswa); ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
            </div>
        </div>
        <a href="<?php echo base_url('admin/siswa'); ?>" class="text-blue-600 text-sm mt-4 inline-block hover:underline">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Total Guru Card -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Guru</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($total_guru); ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
            </div>
        </div>
        <a href="<?php echo base_url('admin/guru'); ?>" class="text-green-600 text-sm mt-4 inline-block hover:underline">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Absensi Siswa Hari Ini Card -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Absen Siswa Hari Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($absen_siswa_today); ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
            </div>
        </div>
        <p class="text-purple-600 text-sm mt-4">
            <?php 
            $persentase_siswa = $total_siswa > 0 ? round(($absen_siswa_today / $total_siswa) * 100) : 0;
            echo $persentase_siswa . '% kehadiran';
            ?>
        </p>
    </div>

    <!-- Absensi Guru Hari Ini Card -->
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Absen Guru Hari Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($absen_guru_today); ?></h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-check text-yellow-600 text-xl"></i>
            </div>
        </div>
        <p class="text-yellow-600 text-sm mt-4">
            <?php 
            $persentase_guru = $total_guru > 0 ? round(($absen_guru_today / $total_guru) * 100) : 0;
            echo $persentase_guru . '% kehadiran';
            ?>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Absensi Siswa Terbaru -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Absensi Siswa Terbaru</h3>
            <a href="<?php echo base_url('admin/laporan_siswa'); ?>" class="text-blue-600 text-sm hover:underline">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($recent_absensi_siswa)): ?>
                        <?php foreach ($recent_absensi_siswa as $absen): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <!-- Will fetch siswa name via join -->
                                    Siswa #<?php echo $absen->user_id; ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <?php echo date('H:i', strtotime($absen->jam_masuk)); ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($absen->status_masuk == 'tepat_waktu'): ?>
                                        <span class="badge badge-success">Tepat Waktu</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Terlambat <?php echo $absen->menit_keterlambatan; ?> menit</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data absensi hari ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Absensi Guru Terbaru -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Absensi Guru Terbaru</h3>
            <a href="<?php echo base_url('admin/laporan_guru'); ?>" class="text-blue-600 text-sm hover:underline">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($recent_absensi_guru)): ?>
                        <?php foreach ($recent_absensi_guru as $absen): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <!-- Will fetch guru name via join -->
                                    Guru #<?php echo $absen->user_id; ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <?php echo date('H:i', strtotime($absen->jam_masuk)); ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($absen->status_masuk == 'tepat_waktu'): ?>
                                        <span class="badge badge-success">Tepat Waktu</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Terlambat <?php echo $absen->menit_keterlambatan; ?> menit</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data absensi hari ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo base_url('admin/siswa/create'); ?>" 
           class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors">
            <i class="fas fa-user-plus text-3xl text-gray-400 mb-2"></i>
            <span class="text-sm text-gray-600">Tambah Siswa</span>
        </a>
        <a href="<?php echo base_url('admin/guru/create'); ?>" 
           class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors">
            <i class="fas fa-user-tie text-3xl text-gray-400 mb-2"></i>
            <span class="text-sm text-gray-600">Tambah Guru</span>
        </a>
        <a href="<?php echo base_url('admin/laporan_siswa'); ?>" 
           class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors">
            <i class="fas fa-file-pdf text-3xl text-gray-400 mb-2"></i>
            <span class="text-sm text-gray-600">Cetak Laporan</span>
        </a>
        <a href="<?php echo base_url('absensi'); ?>" target="_blank"
           class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition-colors">
            <i class="fas fa-qrcode text-3xl text-gray-400 mb-2"></i>
            <span class="text-sm text-gray-600">Absensi RFID</span>
        </a>
    </div>
</div>
