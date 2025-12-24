<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-users mr-2"></i>Laporan Absensi Siswa
        </h1>
        <p class="text-gray-600">Laporan kehadiran siswa per bulan</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= sprintf('%02d', $i) ?>" <?= $bulan == sprintf('%02d', $i) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                    <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    <?php foreach($kelas_list as $k): ?>
                    <option value="<?= $k->id ?>" <?= $kelas_id == $k->id ? 'selected' : '' ?>><?= $k->nama_kelas ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
        <div class="mt-4 flex gap-2">
            <a href="<?= base_url('admin/laporan/pdf_absensi_siswa?' . http_build_query(['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelas_id])) ?>" 
               target="_blank"
               class="inline-block bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
            </a>
            <a href="<?= base_url('admin/laporan/excel_absensi_siswa?' . http_build_query(['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelas_id])) ?>" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-list mr-2"></i>Data Absensi - <?= date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) ?>
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Pulang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($absensi)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            Tidak ada data untuk periode yang dipilih
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($absensi as $index => $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row->nis ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row->nama ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row->nama_kelas ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('d M Y', strtotime($row->tanggal)) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row->jam_masuk ?: '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row->jam_pulang ?: '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($row->terlambat > 0): ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                Terlambat <?= $row->terlambat ?> menit
                            </span>
                            <?php else: ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Tepat Waktu
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
