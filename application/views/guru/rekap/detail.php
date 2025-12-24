<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?= base_url('guru/rekap') ?>" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Rekap
        </a>
    </div>

    <!-- Journal Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Detail Jurnal Mengajar</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Tanggal</p>
                <p class="text-lg font-semibold text-gray-900">
                    <?= date('d F Y', strtotime($jurnal->tanggal)) ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hari</p>
                <p class="text-lg font-semibold text-gray-900"><?= $jurnal->hari ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Mata Pelajaran</p>
                <p class="text-lg font-semibold text-gray-900"><?= $jurnal->nama_mapel ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kelas</p>
                <p class="text-lg font-semibold text-gray-900"><?= $jurnal->nama_kelas ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jam</p>
                <p class="text-lg font-semibold text-gray-900">
                    <?= $jurnal->jam_mulai ?> - <?= $jurnal->jam_selesai ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Material Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-book text-blue-600 mr-2"></i>Materi Pembelajaran
        </h2>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-800 whitespace-pre-line"><?= $jurnal->materi ?></p>
        </div>

        <?php if (!empty($jurnal->catatan)): ?>
            <h2 class="text-xl font-bold text-gray-800 mt-6 mb-4">
                <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>Catatan Tambahan
            </h2>
            <div class="bg-yellow-50 rounded-lg p-4">
                <p class="text-gray-800 whitespace-pre-line"><?= $jurnal->catatan ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Attendance Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
            <div class="text-center">
                <p class="text-green-100 text-sm">Hadir (H)</p>
                <p class="text-3xl font-bold"><?= $stats['H'] ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-4 text-white">
            <div class="text-center">
                <p class="text-yellow-100 text-sm">Sakit (S)</p>
                <p class="text-3xl font-bold"><?= $stats['S'] ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
            <div class="text-center">
                <p class="text-blue-100 text-sm">Izin (I)</p>
                <p class="text-3xl font-bold"><?= $stats['I'] ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-4 text-white">
            <div class="text-center">
                <p class="text-red-100 text-sm">Alpha (A)</p>
                <p class="text-3xl font-bold"><?= $stats['A'] ?></p>
            </div>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
            <h2 class="text-xl font-semibold text-white">Daftar Absensi Siswa</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($absensi as $index => $abs): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $abs->nis ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $abs->nama ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php
                                $status_colors = [
                                    'H' => 'bg-green-100 text-green-800',
                                    'S' => 'bg-yellow-100 text-yellow-800',
                                    'I' => 'bg-blue-100 text-blue-800',
                                    'A' => 'bg-red-100 text-red-800'
                                ];
                                $status_labels = [
                                    'H' => 'Hadir',
                                    'S' => 'Sakit',
                                    'I' => 'Izin',
                                    'A' => 'Alpha'
                                ];
                                $color = $status_colors[$abs->status] ?? 'bg-gray-100 text-gray-800';
                                $label = $status_labels[$abs->status] ?? $abs->status;
                                ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $color ?>">
                                    <?= $label ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
