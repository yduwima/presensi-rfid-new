<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-file-alt mr-2"></i>Laporan
        </h1>
        <p class="text-gray-600">Pilih jenis laporan yang ingin dilihat atau dicetak</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Laporan Absensi Siswa -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                <i class="fas fa-users text-white text-5xl"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Absensi Siswa</h3>
                <p class="text-gray-600 mb-4">Laporan kehadiran siswa per bulan dengan detail jam datang dan pulang</p>
                <a href="<?= base_url('admin/laporan/absensi_siswa') ?>" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Absensi Guru -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                <i class="fas fa-chalkboard-teacher text-white text-5xl"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Absensi Guru</h3>
                <p class="text-gray-600 mb-4">Laporan kehadiran guru per bulan dengan detail jam datang dan pulang</p>
                <a href="<?= base_url('admin/laporan/absensi_guru') ?>" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Rekap Jurnal -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                <i class="fas fa-book text-white text-5xl"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Rekap Jurnal</h3>
                <p class="text-gray-600 mb-4">Rekap jurnal mengajar guru per bulan dengan detail materi dan absensi</p>
                <a href="<?= base_url('admin/laporan/rekap_jurnal') ?>" 
                   class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-blue-800 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi
        </h3>
        <ul class="space-y-2 text-gray-700">
            <li><i class="fas fa-check text-blue-600 mr-2"></i>Semua laporan dapat difilter berdasarkan bulan dan tahun</li>
            <li><i class="fas fa-check text-blue-600 mr-2"></i>Laporan absensi siswa dapat difilter per kelas</li>
            <li><i class="fas fa-check text-blue-600 mr-2"></i>Laporan dapat dicetak dalam format PDF</li>
            <li><i class="fas fa-check text-blue-600 mr-2"></i>Data ditampilkan secara real-time dari database</li>
        </ul>
    </div>
</div>
