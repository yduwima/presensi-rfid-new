<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Pengaturan WhatsApp Notifikasi</h1>
        <p class="text-gray-600 mt-2">Konfigurasi API WhatsApp dan template notifikasi</p>
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

    <!-- API Configuration -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-cog text-blue-600 mr-2"></i>Konfigurasi API
        </h2>
        
        <form action="<?= base_url('admin/wa_settings/save') ?>" method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp API URL</label>
                    <input type="text" name="wa_url" value="<?= $wa_url ?>" 
                           placeholder="https://api.fonnte.com/send"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">URL endpoint API WhatsApp (contoh: Fonnte)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="wa_api_key" value="<?= $wa_api_key ?>" 
                           placeholder="Your API Key"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender Number</label>
                    <input type="text" name="wa_sender" value="<?= $wa_sender ?>" 
                           placeholder="628123456789"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nomor pengirim dengan format internasional</p>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition">
                    <i class="fas fa-save mr-2"></i>Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>

    <!-- Message Templates -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-comments text-green-600 mr-2"></i>Template Pesan
        </h2>
        
        <div class="space-y-4">
            <?php if (!empty($templates)): ?>
                <?php foreach ($templates as $t): ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <form action="<?= base_url('admin/wa_settings/update_template') ?>" method="post">
                            <input type="hidden" name="id" value="<?= $t->id ?>">
                            
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucwords(str_replace('_', ' ', $t->type)) ?>
                            </label>
                            
                            <textarea name="template" rows="4" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 mb-2"><?= $t->template ?></textarea>
                            
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-500">
                                    Variabel: {nama}, {kelas}, {waktu}, {tanggal}, {status}
                                </p>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1.5 rounded-md text-sm transition">
                                    <i class="fas fa-save mr-1"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada template pesan.</p>
                    <p class="text-sm">Silakan tambahkan data template di database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Class Notification Settings -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bell text-yellow-600 mr-2"></i>Pengaturan Notifikasi Per Kelas
        </h2>
        <p class="text-gray-600 mb-4">Pilih kelas yang akan menerima notifikasi WhatsApp</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php 
            $enabled_kelas = [];
            foreach ($wa_notif_kelas as $nk) {
                $enabled_kelas[] = $nk->kelas_id;
            }
            
            foreach ($kelas as $k): 
                $is_enabled = in_array($k->id, $enabled_kelas);
            ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800"><?= $k->nama_kelas ?></p>
                            <p class="text-sm text-gray-500">Tingkat <?= $k->tingkat ?></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   class="sr-only peer" 
                                   data-kelas-id="<?= $k->id ?>"
                                   <?= $is_enabled ? 'checked' : '' ?>
                                   onchange="toggleKelas(this)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Notifikasi akan dikirim otomatis saat siswa melakukan absensi masuk/pulang via RFID</li>
                        <li>Template pesan mendukung variabel dinamis seperti {nama}, {kelas}, {waktu}</li>
                        <li>Hanya kelas yang diaktifkan yang akan menerima notifikasi WhatsApp</li>
                        <li>Pastikan API Key dan Sender Number sudah dikonfigurasi dengan benar</li>
                        <li>Sistem menggunakan queue untuk menghindari blocking saat pengiriman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleKelas(checkbox) {
    const kelasId = checkbox.getAttribute('data-kelas-id');
    const enabled = checkbox.checked ? '1' : '0';
    
    fetch('<?= base_url('admin/wa_settings/toggle_kelas') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'kelas_id=' + kelasId + '&enabled=' + enabled
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
            alert('Gagal mengubah pengaturan');
        }
    })
    .catch(error => {
        checkbox.checked = !checkbox.checked;
        alert('Terjadi kesalahan');
    });
}
</script>
