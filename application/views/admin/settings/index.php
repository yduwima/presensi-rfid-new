<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Pengaturan Sekolah</h2>
    <p class="text-gray-600 mt-1">Kelola informasi sekolah dan konfigurasi umum</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="<?php echo base_url('admin/settings/update'); ?>" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Sekolah -->
            <div class="md:col-span-2">
                <label for="nama_sekolah" class="form-label">Nama Sekolah <span class="text-red-500">*</span></label>
                <input type="text" class="form-input" id="nama_sekolah" name="nama_sekolah" 
                       value="<?php echo $settings ? $settings->nama_sekolah : ''; ?>" required>
            </div>

            <!-- Alamat Sekolah -->
            <div class="md:col-span-2">
                <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                <textarea class="form-input" id="alamat_sekolah" name="alamat_sekolah" rows="3"><?php echo $settings ? $settings->alamat_sekolah : ''; ?></textarea>
            </div>

            <!-- Nama Kepala Sekolah -->
            <div>
                <label for="nama_kepala_sekolah" class="form-label">Nama Kepala Sekolah</label>
                <input type="text" class="form-input" id="nama_kepala_sekolah" name="nama_kepala_sekolah" 
                       value="<?php echo $settings ? $settings->nama_kepala_sekolah : ''; ?>">
            </div>

            <!-- Website -->
            <div>
                <label for="website" class="form-label">Website</label>
                <input type="url" class="form-input" id="website" name="website" 
                       value="<?php echo $settings ? $settings->website : ''; ?>"
                       placeholder="https://example.com">
            </div>

            <!-- No. Telephone -->
            <div>
                <label for="no_telephone" class="form-label">No. Telephone</label>
                <input type="text" class="form-input" id="no_telephone" name="no_telephone" 
                       value="<?php echo $settings ? $settings->no_telephone : ''; ?>"
                       placeholder="021-1234567">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-input" id="email" name="email" 
                       value="<?php echo $settings ? $settings->email : ''; ?>"
                       placeholder="info@sekolah.com">
            </div>

            <!-- Logo -->
            <div class="md:col-span-2">
                <label for="logo" class="form-label">Logo Sekolah</label>
                <?php if ($settings && $settings->logo): ?>
                    <div class="mb-3">
                        <img src="<?php echo base_url('assets/uploads/' . $settings->logo); ?>" 
                             alt="Logo Sekolah" 
                             class="h-24 object-contain border border-gray-300 rounded-lg p-2">
                        <p class="text-xs text-gray-500 mt-1">Logo saat ini</p>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-input" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<!-- Preview Section -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Kop Surat</h3>
    <div class="border-2 border-gray-300 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <?php if ($settings && $settings->logo): ?>
                <img src="<?php echo base_url('assets/uploads/' . $settings->logo); ?>" 
                     alt="Logo" 
                     class="h-20 object-contain">
            <?php else: ?>
                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-school text-gray-400 text-3xl"></i>
                </div>
            <?php endif; ?>
            
            <div class="text-center flex-1 mx-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?php echo $settings ? $settings->nama_sekolah : 'NAMA SEKOLAH'; ?>
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <?php echo $settings ? $settings->alamat_sekolah : 'Alamat Sekolah'; ?>
                </p>
                <p class="text-sm text-gray-600">
                    Telp: <?php echo $settings ? $settings->no_telephone : '021-xxxxxx'; ?> | 
                    Email: <?php echo $settings ? $settings->email : 'email@sekolah.com'; ?>
                </p>
                <?php if ($settings && $settings->website): ?>
                    <p class="text-sm text-gray-600">Website: <?php echo $settings->website; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="w-20"></div>
        </div>
        <hr class="border-t-2 border-gray-800 mt-4">
    </div>
</div>
