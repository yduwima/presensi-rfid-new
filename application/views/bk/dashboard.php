<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Problem Students -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Siswa Bermasalah</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_problem_students; ?></p>
            </div>
        </div>
    </div>

    <!-- Total Alpha Students -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                <i class="fas fa-user-slash text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Alpha ≥ 3x</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_alpha_students; ?></p>
            </div>
        </div>
    </div>

    <!-- Total Late Students -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Terlambat ≥ 5x</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_late_students; ?></p>
            </div>
        </div>
    </div>

    <!-- Total Letters This Month -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                <i class="fas fa-envelope text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Surat Bulan Ini</p>
                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_letters_month; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-lg shadow-lg p-6 text-white">
        <h3 class="text-xl font-bold mb-2">
            <i class="fas fa-chart-line mr-2"></i>
            Monitoring Siswa
        </h3>
        <p class="mb-4">Pantau siswa dengan masalah kedisiplinan (alpha dan keterlambatan)</p>
        <a href="<?php echo base_url('bk/monitoring'); ?>" class="inline-block bg-white text-purple-700 px-6 py-2 rounded-lg font-semibold hover:bg-purple-50">
            Lihat Monitoring
        </a>
    </div>

    <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-lg shadow-lg p-6 text-white">
        <h3 class="text-xl font-bold mb-2">
            <i class="fas fa-file-alt mr-2"></i>
            Surat Panggilan
        </h3>
        <p class="mb-4">Buat dan kelola surat panggilan orang tua siswa</p>
        <a href="<?php echo base_url('bk/surat'); ?>" class="inline-block bg-white text-indigo-700 px-6 py-2 rounded-lg font-semibold hover:bg-indigo-50">
            Kelola Surat
        </a>
    </div>
</div>

<!-- Recent Letters -->
<?php if (!empty($recent_letters)): ?>
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-history mr-2"></i>
        Surat Terbaru
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($recent_letters as $letter): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo date('d/m/Y', strtotime($letter->tanggal)); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo $letter->nomor_surat; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo $letter->nama_siswa; ?> (<?php echo $letter->nis; ?>)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo $letter->jenis == 'alpha' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                            <?php echo $letter->jenis == 'alpha' ? 'Alpha' : 'Terlambat'; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
