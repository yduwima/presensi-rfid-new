<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Import Data Guru</h2>
            <a href="<?= base_url('admin/guru') ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Upload Form -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Upload File Excel</h3>
                
                <form action="<?= base_url('admin/guru/process_import') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih File Excel
                        </label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Format: XLSX, XLS, atau CSV (Max. 5MB)</p>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Catatan:</strong>
                                </p>
                                <ul class="list-disc list-inside text-sm text-blue-600 mt-1">
                                    <li>Pastikan format file sesuai template</li>
                                    <li>NIP harus unik (tidak boleh duplikat)</li>
                                    <li>Email harus valid</li>
                                    <li>Wali Kelas, Piket, BK: isi 0 atau 1</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-upload mr-2"></i>Upload dan Import
                    </button>
                </form>
            </div>

            <!-- Template Download -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Download Template</h3>
                
                <div class="bg-gray-50 border border-gray-200 rounded p-4 space-y-4">
                    <p class="text-sm text-gray-600">
                        Download template Excel untuk memudahkan proses import data guru dan staff.
                    </p>

                    <a href="<?= base_url('admin/guru/download_template') ?>" 
                       class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-download mr-2"></i>Download Template
                    </a>

                    <div class="mt-4">
                        <h4 class="font-semibold text-sm mb-2">Kolom yang Harus Diisi:</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>NIP</strong> - Nomor Induk Pegawai (wajib, unik)
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Nama</strong> - Nama lengkap guru (wajib)
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Email</strong> - Alamat email yang valid
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>No HP</strong> - Nomor telepon guru
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Alamat</strong> - Alamat lengkap
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Wali Kelas</strong> - 1 jika wali kelas, 0 jika tidak
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Piket</strong> - 1 jika guru piket, 0 jika tidak
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>BK</strong> - 1 jika guru BK, 0 jika tidak
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check text-gray-400 mr-2 mt-1"></i>
                                <div>
                                    <strong>RFID UID</strong> - Nomor kartu RFID (opsional)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Penting!</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Data yang sudah ada (berdasarkan NIP) akan dilewati (tidak duplikat)</li>
                            <li>Data yang tidak valid akan di-skip dan ditampilkan dalam laporan error</li>
                            <li>Proses import akan memvalidasi semua data sebelum menyimpan ke database</li>
                            <li>Satu guru bisa memiliki multiple role (Wali Kelas + Piket + BK)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
