<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Filter Surat</h3>
        <a href="<?php echo base_url('bk/surat/create'); ?>" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            <i class="fas fa-plus mr-2"></i>Buat Surat Baru
        </a>
    </div>
    <form method="get" class="flex gap-2">
        <select name="bulan" class="border border-gray-300 rounded px-4 py-2">
            <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo $bulan == $i ? 'selected' : ''; ?>>
                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
            </option>
            <?php endfor; ?>
        </select>
        <select name="tahun" class="border border-gray-300 rounded px-4 py-2">
            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
            <option value="<?php echo $i; ?>" <?php echo $tahun == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Daftar Surat Panggilan</h3>
    <?php if (!empty($letters)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($letters as $letter): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $letter->nomor_surat; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo date('d/m/Y', strtotime($letter->tanggal_surat)); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $letter->nama_siswa; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $letter->nama_kelas; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            <?php echo ucfirst($letter->status); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="<?php echo base_url('bk/surat/pdf/' . $letter->id); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="<?php echo base_url('bk/surat/edit/' . $letter->id); ?>" class="text-green-600 hover:text-green-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo base_url('bk/surat/delete/' . $letter->id); ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus surat ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-inbox text-6xl mb-4"></i>
        <p class="text-lg">Belum ada surat untuk periode ini</p>
    </div>
    <?php endif; ?>
</div>
