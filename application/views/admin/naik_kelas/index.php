<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Naik Kelas</h2>
        <p class="text-gray-600">Promosi siswa ke tingkat/kelas berikutnya</p>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            <p><?php echo $this->session->flashdata('success'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <p><?php echo $this->session->flashdata('error'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Petunjuk Penggunaan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Pilih kelas asal yang akan dinaikkan</li>
                        <li>Pilih tahun ajaran tujuan (biasanya tahun ajaran baru)</li>
                        <li>Sistem akan otomatis menampilkan kelas tujuan berdasarkan tingkat</li>
                        <li>Untuk kelas XII, pilih opsi "Luluskan" untuk mengarsipkan siswa</li>
                        <li>Klik "Proses Kenaikan Kelas" untuk melakukan promosi massal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Wizard -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Step Indicators -->
        <div class="flex border-b border-gray-200">
            <div id="step1-indicator" class="flex-1 text-center py-4 border-b-2 border-blue-500 font-semibold text-blue-600">
                <i class="fas fa-search mr-2"></i>Pilih Kelas
            </div>
            <div id="step2-indicator" class="flex-1 text-center py-4 border-b-2 border-gray-300 text-gray-500">
                <i class="fas fa-users mr-2"></i>Preview Siswa
            </div>
            <div id="step3-indicator" class="flex-1 text-center py-4 border-b-2 border-gray-300 text-gray-500">
                <i class="fas fa-check mr-2"></i>Konfirmasi
            </div>
        </div>

        <div class="p-6">
            <!-- Step 1: Select Class -->
            <div id="step1" class="step">
                <h3 class="text-lg font-semibold mb-4">Pilih Kelas Asal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Asal</label>
                        <select id="kelas_asal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <option value="<?php echo $kelas->id; ?>" data-tingkat="<?php echo $kelas->tingkat; ?>">
                                    <?php echo $kelas->nama_kelas; ?> (Tingkat <?php echo $kelas->tingkat; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran Tujuan</label>
                        <select id="tahun_ajaran_tujuan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <?php foreach ($tahun_ajaran_list as $ta): ?>
                                <option value="<?php echo $ta->id; ?>">
                                    <?php echo $ta->tahun; ?> <?php echo $ta->is_active ? '(Aktif)' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="loadPreview()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-arrow-right mr-2"></i>Lanjut ke Preview
                    </button>
                </div>
            </div>

            <!-- Step 2: Preview Students -->
            <div id="step2" class="step hidden">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Preview Siswa</h3>
                    <div id="student-count" class="text-sm text-gray-600"></div>
                </div>

                <div id="preview-content" class="mb-4">
                    <!-- Preview will be loaded here via AJAX -->
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="backToStep1()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </button>
                    <button type="button" onclick="goToConfirmation()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-arrow-right mr-2"></i>Lanjut ke Konfirmasi
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div id="step3" class="step hidden">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Kenaikan Kelas</h3>

                <div id="confirmation-content" class="mb-6">
                    <!-- Confirmation details will be loaded here -->
                </div>

                <form id="form-naik-kelas" method="POST" action="<?php echo base_url('admin/naik_kelas/process_naik_kelas'); ?>">
                    <input type="hidden" name="kelas_asal_id" id="hidden_kelas_asal">
                    <input type="hidden" name="kelas_tujuan_id" id="hidden_kelas_tujuan">
                    <input type="hidden" name="tahun_ajaran_tujuan" id="hidden_tahun_ajaran">
                    <input type="hidden" name="action" id="hidden_action">

                    <div class="mt-6 flex justify-between">
                        <button type="button" onclick="backToStep2()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Proses Kenaikan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 1;
let kelasAsalData = null;
let siswaData = null;
let kelasTujuanData = null;
let willGraduate = false;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step').forEach(el => el.classList.add('hidden'));
    
    // Update indicators
    for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById(`step${i}-indicator`);
        if (i < step) {
            indicator.className = 'flex-1 text-center py-4 border-b-2 border-green-500 font-semibold text-green-600';
        } else if (i === step) {
            indicator.className = 'flex-1 text-center py-4 border-b-2 border-blue-500 font-semibold text-blue-600';
        } else {
            indicator.className = 'flex-1 text-center py-4 border-b-2 border-gray-300 text-gray-500';
        }
    }
    
    // Show current step
    document.getElementById(`step${step}`).classList.remove('hidden');
    currentStep = step;
}

function loadPreview() {
    const kelasAsalId = document.getElementById('kelas_asal').value;
    const tahunAjaranTujuan = document.getElementById('tahun_ajaran_tujuan').value;
    
    if (!kelasAsalId || !tahunAjaranTujuan) {
        alert('Mohon pilih kelas asal dan tahun ajaran tujuan');
        return;
    }
    
    const selectedKelas = document.getElementById('kelas_asal');
    const tingkatAsal = selectedKelas.options[selectedKelas.selectedIndex].dataset.tingkat;
    
    // Load student data
    fetch('<?php echo base_url("admin/naik_kelas/get_siswa_by_kelas"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `kelas_id=${kelasAsalId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        siswaData = data;
        kelasAsalData = data.kelas_info;
        
        // Load target class options
        fetch('<?php echo base_url("admin/naik_kelas/get_kelas_tujuan"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `tingkat_asal=${tingkatAsal}&tahun_ajaran_tujuan=${tahunAjaranTujuan}`
        })
        .then(response => response.json())
        .then(kelasData => {
            willGraduate = kelasData.graduate || false;
            kelasTujuanData = kelasData;
            
            renderPreview();
            showStep(2);
        });
    });
}

function renderPreview() {
    const previewContent = document.getElementById('preview-content');
    const studentCount = document.getElementById('student-count');
    
    studentCount.innerHTML = `<i class="fas fa-users mr-2"></i>Total: ${siswaData.total} siswa`;
    
    let html = `
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold mb-2">Kelas Asal:</h4>
            <p class="text-gray-700">${kelasAsalData.nama_kelas} (Tingkat ${kelasAsalData.tingkat})</p>
        </div>
    `;
    
    if (willGraduate) {
        html += `
            <div class="bg-yellow-50 border border-yellow-300 p-4 rounded-lg mb-4">
                <i class="fas fa-graduation-cap text-yellow-600 mr-2"></i>
                <span class="font-semibold text-yellow-800">Siswa akan LULUS (Kelas XII)</span>
                <p class="text-sm text-yellow-700 mt-2">Siswa akan diarsipkan sebagai alumni</p>
            </div>
        `;
    } else {
        html += `
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Pilih Kelas Tujuan:</h4>
                <select id="kelas_tujuan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kelas Tujuan --</option>
        `;
        
        kelasTujuanData.kelas.forEach(kelas => {
            html += `<option value="${kelas.id}">${kelas.nama_kelas} (Tingkat ${kelas.tingkat})</option>`;
        });
        
        html += `
                </select>
            </div>
        `;
    }
    
    html += `
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    siswaData.siswa.forEach((siswa, index) => {
        html += `
            <tr>
                <td class="px-4 py-3 text-sm">${index + 1}</td>
                <td class="px-4 py-3 text-sm">${siswa.nis}</td>
                <td class="px-4 py-3 text-sm font-medium">${siswa.nama}</td>
                <td class="px-4 py-3 text-sm">${siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    previewContent.innerHTML = html;
}

function goToConfirmation() {
    let kelasTujuanId = null;
    let kelasTujuanNama = '';
    let action = willGraduate ? 'graduate' : 'promote';
    
    if (!willGraduate) {
        kelasTujuanId = document.getElementById('kelas_tujuan').value;
        if (!kelasTujuanId) {
            alert('Mohon pilih kelas tujuan');
            return;
        }
        
        const selectKelas = document.getElementById('kelas_tujuan');
        kelasTujuanNama = selectKelas.options[selectKelas.selectedIndex].text;
    }
    
    // Set hidden inputs
    document.getElementById('hidden_kelas_asal').value = document.getElementById('kelas_asal').value;
    document.getElementById('hidden_kelas_tujuan').value = kelasTujuanId || '';
    document.getElementById('hidden_tahun_ajaran').value = document.getElementById('tahun_ajaran_tujuan').value;
    document.getElementById('hidden_action').value = action;
    
    // Render confirmation
    const confirmationContent = document.getElementById('confirmation-content');
    
    let html = `
        <div class="bg-gray-50 p-6 rounded-lg">
            <h4 class="font-semibold text-lg mb-4">Ringkasan Kenaikan Kelas</h4>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Kelas Asal:</p>
                    <p class="font-semibold">${kelasAsalData.nama_kelas}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Siswa:</p>
                    <p class="font-semibold">${siswaData.total} siswa</p>
                </div>
            </div>
    `;
    
    if (willGraduate) {
        html += `
            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mt-4">
                <p class="font-semibold text-yellow-800">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Siswa akan LULUS dan diarsipkan sebagai alumni
                </p>
            </div>
        `;
    } else {
        html += `
            <div class="mt-4">
                <p class="text-sm text-gray-600">Kelas Tujuan:</p>
                <p class="font-semibold">${kelasTujuanNama}</p>
            </div>
            
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mt-4">
                <p class="font-semibold text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    ${siswaData.total} siswa akan dipindahkan ke ${kelasTujuanNama}
                </p>
            </div>
        `;
    }
    
    html += `
        </div>
        
        <div class="bg-red-50 border border-red-200 p-4 rounded-lg mt-4">
            <p class="text-sm text-red-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian:</strong> Proses ini akan mengubah data kelas siswa secara permanen. Pastikan data sudah benar sebelum melanjutkan.
            </p>
        </div>
    `;
    
    confirmationContent.innerHTML = html;
    showStep(3);
}

function backToStep1() {
    showStep(1);
}

function backToStep2() {
    showStep(2);
}

// Initialize
showStep(1);
</script>
