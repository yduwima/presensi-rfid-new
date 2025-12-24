<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi RFID - Real Time</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-600 to-purple-600 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">ABSENSI RFID</h1>
            <p class="text-white text-lg" id="current-time"></p>
            <p class="text-white text-sm" id="current-date"></p>
        </div>

        <!-- Scan Area -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="text-center mb-6">
                    <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <i class="fas fa-id-card text-blue-600 text-6xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Tempelkan Kartu RFID</h2>
                    <p class="text-gray-600">Sistem akan otomatis mendeteksi kartu Anda</p>
                </div>

                <!-- Manual Input for Testing -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Manual Input (Testing):
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="rfid-input" 
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan RFID UID..."
                            autofocus>
                        <button onclick="processRFID()" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>Scan
                        </button>
                    </div>
                </div>

                <!-- Result Message -->
                <div id="scan-result" class="mt-6 hidden"></div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Siswa -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user-graduate text-blue-600 mr-2"></i>
                        Absensi Siswa Hari Ini
                    </h3>
                    <span class="badge badge-info" id="siswa-count">0</span>
                </div>
                <div id="siswa-list" class="space-y-2 max-h-96 overflow-y-auto">
                    <p class="text-center text-gray-500 py-8">Belum ada data</p>
                </div>
            </div>

            <!-- Guru -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                        Absensi Guru Hari Ini
                    </h3>
                    <span class="badge badge-success" id="guru-count">0</span>
                </div>
                <div id="guru-list" class="space-y-2 max-h-96 overflow-y-auto">
                    <p class="text-center text-gray-500 py-8">Belum ada data</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 animate-bounce">
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2" id="modal-title">Berhasil!</h3>
                <p class="text-gray-600 mb-1" id="modal-name"></p>
                <p class="text-gray-600 text-sm" id="modal-time"></p>
                <p class="text-gray-600 text-sm" id="modal-status"></p>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = '<?php echo base_url(); ?>';
        
        // Update clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            document.getElementById('current-time').textContent = timeStr;
            document.getElementById('current-date').textContent = dateStr;
        }
        
        setInterval(updateClock, 1000);
        updateClock();

        // Auto focus on RFID input
        document.getElementById('rfid-input').focus();

        // Process RFID
        function processRFID() {
            const rfidInput = document.getElementById('rfid-input');
            const rfidUid = rfidInput.value.trim();
            
            if (!rfidUid) {
                showError('Silakan masukkan RFID UID');
                return;
            }

            // Send to server
            fetch(baseUrl + 'absensi/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'rfid_uid=' + encodeURIComponent(rfidUid)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data);
                    rfidInput.value = '';
                    loadRecentAttendance();
                } else {
                    showError(data.message);
                }
                rfidInput.focus();
            })
            .catch(error => {
                showError('Terjadi kesalahan: ' + error);
                rfidInput.focus();
            });
        }

        // Enter key to scan
        document.getElementById('rfid-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processRFID();
            }
        });

        // Show success modal
        function showSuccess(data) {
            const modal = document.getElementById('success-modal');
            document.getElementById('modal-title').textContent = data.action === 'masuk' ? 'Absen Masuk Berhasil!' : 'Absen Pulang Berhasil!';
            document.getElementById('modal-name').textContent = data.name;
            document.getElementById('modal-time').textContent = 'Waktu: ' + data.time;
            
            if (data.action === 'masuk') {
                const statusText = data.status === 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat ' + data.late_minutes + ' menit';
                document.getElementById('modal-status').textContent = 'Status: ' + statusText;
            } else {
                document.getElementById('modal-status').textContent = '';
            }
            
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 3000);
        }

        // Show error
        function showError(message) {
            const resultDiv = document.getElementById('scan-result');
            resultDiv.className = 'mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center';
            resultDiv.innerHTML = `
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>${message}</span>
            `;
            resultDiv.classList.remove('hidden');
            
            setTimeout(() => {
                resultDiv.classList.add('hidden');
            }, 3000);
        }

        // Load recent attendance
        function loadRecentAttendance() {
            fetch(baseUrl + 'absensi/get_recent?limit=10')
            .then(response => response.json())
            .then(data => {
                // Update siswa list
                const siswaList = document.getElementById('siswa-list');
                if (data.siswa && data.siswa.length > 0) {
                    siswaList.innerHTML = data.siswa.map(item => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">${item.nama}</p>
                                <p class="text-xs text-gray-500">${item.kelas}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">${item.jam_masuk ? item.jam_masuk.substring(0, 5) : '-'}</p>
                                ${item.status_masuk === 'terlambat' ? 
                                    `<span class="badge badge-warning text-xs">Terlambat</span>` : 
                                    `<span class="badge badge-success text-xs">Tepat Waktu</span>`
                                }
                            </div>
                        </div>
                    `).join('');
                    document.getElementById('siswa-count').textContent = data.siswa.length;
                } else {
                    siswaList.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada data</p>';
                    document.getElementById('siswa-count').textContent = '0';
                }

                // Update guru list
                const guruList = document.getElementById('guru-list');
                if (data.guru && data.guru.length > 0) {
                    guruList.innerHTML = data.guru.map(item => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">${item.nama}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">${item.jam_masuk ? item.jam_masuk.substring(0, 5) : '-'}</p>
                                ${item.status_masuk === 'terlambat' ? 
                                    `<span class="badge badge-warning text-xs">Terlambat</span>` : 
                                    `<span class="badge badge-success text-xs">Tepat Waktu</span>`
                                }
                            </div>
                        </div>
                    `).join('');
                    document.getElementById('guru-count').textContent = data.guru.length;
                } else {
                    guruList.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada data</p>';
                    document.getElementById('guru-count').textContent = '0';
                }
            });
        }

        // Load initial data
        loadRecentAttendance();
        
        // Auto refresh every 5 seconds
        setInterval(loadRecentAttendance, 5000);
    </script>
</body>
</html>
