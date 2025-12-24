<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Pengaturan Hari Kerja</h2>
    <p class="text-gray-600 mt-1">Atur jam masuk dan pulang untuk setiap hari kerja</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="<?php echo base_url('admin/hari_kerja/update_all'); ?>">
        <div class="space-y-4">
            <?php 
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $colors = [
                'Senin' => 'blue',
                'Selasa' => 'green', 
                'Rabu' => 'yellow',
                'Kamis' => 'purple',
                'Jumat' => 'pink',
                'Sabtu' => 'indigo',
                'Minggu' => 'red'
            ];
            
            foreach ($hari_kerja as $hk): 
                $color = $colors[$hk->hari];
            ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:border-<?php echo $color; ?>-300 transition-colors">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                        <!-- Day Name -->
                        <div class="md:col-span-1">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active_<?php echo $hk->hari; ?>" 
                                       id="is_active_<?php echo $hk->hari; ?>"
                                       <?php echo $hk->is_active ? 'checked' : ''; ?>
                                       class="h-5 w-5 text-<?php echo $color; ?>-600 focus:ring-<?php echo $color; ?>-500 border-gray-300 rounded mr-3">
                                <label for="is_active_<?php echo $hk->hari; ?>" 
                                       class="text-lg font-semibold text-gray-800 cursor-pointer">
                                    <?php echo $hk->hari; ?>
                                </label>
                            </div>
                        </div>

                        <!-- Jam Masuk -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jam Masuk
                            </label>
                            <input type="time" 
                                   name="jam_masuk_<?php echo $hk->hari; ?>" 
                                   value="<?php echo $hk->jam_masuk; ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-<?php echo $color; ?>-500"
                                   required>
                        </div>

                        <!-- Jam Pulang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jam Pulang
                            </label>
                            <input type="time" 
                                   name="jam_pulang_<?php echo $hk->hari; ?>" 
                                   value="<?php echo $hk->jam_pulang; ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-<?php echo $color; ?>-500"
                                   required>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-center">
                            <?php if ($hk->is_active): ?>
                                <span class="badge badge-success">Hari Kerja</span>
                            <?php else: ?>
                                <span class="badge bg-gray-200 text-gray-800">Libur</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<!-- Info Card -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Centang checkbox untuk mengaktifkan hari kerja</li>
                    <li>Hari yang tidak dicentang akan dianggap hari libur</li>
                    <li>Jam masuk digunakan untuk menghitung keterlambatan</li>
                    <li>Sistem absensi RFID akan menggunakan pengaturan ini</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-update status badge when checkbox changed
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const badge = this.closest('.grid').querySelector('.badge');
            if (this.checked) {
                badge.className = 'badge badge-success';
                badge.textContent = 'Hari Kerja';
            } else {
                badge.className = 'badge bg-gray-200 text-gray-800';
                badge.textContent = 'Libur';
            }
        });
    });
</script>
