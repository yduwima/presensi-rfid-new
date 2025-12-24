<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">
            <i class="fas fa-filter mr-2"></i>
            Filter Monitoring
        </h3>
        <form method="get" class="flex gap-2">
            <select name="filter" class="border border-gray-300 rounded px-4 py-2" onchange="this.form.submit()">
                <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Semua Masalah</option>
                <option value="alpha" <?php echo $filter == 'alpha' ? 'selected' : ''; ?>>Alpha ≥ 3x</option>
                <option value="terlambat" <?php echo $filter == 'terlambat' ? 'selected' : ''; ?>>Terlambat ≥ 5x</option>
            </select>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        Daftar Siswa Bermasalah - <?php echo date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)); ?>
    </h3>

    <?php if (!empty($students)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masalah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($students as $student): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $student->nis; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $student->nama; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $student->nama_kelas; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo $student->problem_type == 'alpha' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                            <?php echo $student->problem_type == 'alpha' ? 'Alpha' : 'Terlambat'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-semibold"><?php echo $student->problem_count; ?>x</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="<?php echo base_url('bk/monitoring/detail/' . $student->id); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="<?php echo base_url('bk/surat/create/' . $student->id); ?>" class="text-purple-600 hover:text-purple-900">
                            <i class="fas fa-file-alt"></i> Buat Surat
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-check-circle text-6xl mb-4"></i>
        <p class="text-lg">Tidak ada siswa bermasalah bulan ini</p>
    </div>
    <?php endif; ?>
</div>
