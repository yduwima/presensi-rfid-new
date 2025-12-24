<div class="mb-4">
    <a href="<?php echo base_url('bk/monitoring'); ?>" class="text-purple-600 hover:text-purple-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Monitoring
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Student Info Card -->
    <div class="lg:col-span-3 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Informasi Siswa</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">NIS</p>
                <p class="font-semibold"><?php echo $siswa->nis; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nama</p>
                <p class="font-semibold"><?php echo $siswa->nama; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kelas</p>
                <p class="font-semibold"><?php echo $siswa->nama_kelas; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Orang Tua</p>
                <p class="font-semibold"><?php echo $siswa->nama_ortu; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Alpha Statistics -->
    <div class="bg-red-50 rounded-lg shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-red-500 rounded-full p-3">
                <i class="fas fa-user-slash text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-red-600">Total Alpha</p>
                <p class="text-3xl font-bold text-red-700"><?php echo $total_alpha; ?>x</p>
            </div>
        </div>
    </div>

    <!-- Late Statistics -->
    <div class="bg-yellow-50 rounded-lg shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-yellow-500 rounded-full p-3">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-yellow-600">Total Terlambat</p>
                <p class="text-3xl font-bold text-yellow-700"><?php echo $total_late; ?>x</p>
            </div>
        </div>
    </div>
</div>

<!-- Detail Records -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Alpha Records -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-red-700 mb-4">
            <i class="fas fa-list mr-2"></i>
            Riwayat Tidak Masuk (Alpha)
        </h3>
        <?php if (!empty($alpha_records)): ?>
        <div class="space-y-2">
            <?php foreach ($alpha_records as $record): ?>
            <div class="border-l-4 border-red-500 pl-4 py-2 bg-red-50">
                <p class="font-semibold"><?php echo date('d F Y', strtotime($record->tanggal)); ?></p>
                <p class="text-sm text-gray-600">Jam Masuk: <?php echo $record->jam_masuk ? $record->jam_masuk : '-'; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-500 text-center py-4">Tidak ada record alpha</p>
        <?php endif; ?>
    </div>

    <!-- Late Records -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-yellow-700 mb-4">
            <i class="fas fa-list mr-2"></i>
            Riwayat Keterlambatan
        </h3>
        <?php if (!empty($late_records)): ?>
        <div class="space-y-2">
            <?php foreach ($late_records as $record): ?>
            <div class="border-l-4 border-yellow-500 pl-4 py-2 bg-yellow-50">
                <p class="font-semibold"><?php echo date('d F Y', strtotime($record->tanggal)); ?></p>
                <p class="text-sm text-gray-600">Jam Masuk: <?php echo $record->jam_masuk; ?></p>
                <p class="text-sm text-red-600">Terlambat: <?php echo $record->menit_keterlambatan; ?> menit</p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-500 text-center py-4">Tidak ada record keterlambatan</p>
        <?php endif; ?>
    </div>
</div>

<div class="mt-6 text-center">
    <a href="<?php echo base_url('bk/surat/create/' . $siswa->id); ?>" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
        <i class="fas fa-file-alt mr-2"></i>
        Buat Surat Panggilan
    </a>
</div>
