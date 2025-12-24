<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Profile Saya</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profile dan keamanan akun Anda</p>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user text-6xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800"><?= $guru->nama ?></h2>
                    <p class="text-gray-600 text-sm mt-1">NIP: <?= $guru->nip ?></p>
                    
                    <div class="mt-4 space-y-2">
                        <?php if ($guru->is_wali_kelas): ?>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                <i class="fas fa-users mr-1"></i>Wali Kelas
                            </span>
                        <?php endif; ?>
                        <?php if ($guru->is_piket): ?>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                <i class="fas fa-clipboard-check mr-1"></i>Piket
                            </span>
                        <?php endif; ?>
                        <?php if ($guru->is_bk): ?>
                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                <i class="fas fa-user-tie mr-1"></i>BK
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-envelope w-6 text-gray-400"></i>
                            <span class="text-gray-600"><?= $guru->email ?></span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-phone w-6 text-gray-400"></i>
                            <span class="text-gray-600"><?= $guru->telp ?></span>
                        </div>
                        <?php if ($guru->rfid_uid): ?>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-id-card w-6 text-gray-400"></i>
                                <span class="text-gray-600"><?= $guru->rfid_uid ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div class="md:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>Edit Profile
                </h2>
                
                <form action="<?= base_url('guru/profile/update') ?>" method="post">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                            <input type="text" value="<?= $guru->nip ?>" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama" value="<?= $guru->nama ?>" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="<?= $guru->email ?>" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                            <input type="text" name="telp" value="<?= $guru->telp ?>" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= $guru->alamat ?></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-lock text-yellow-600 mr-2"></i>Ubah Password
                </h2>
                
                <form action="<?= base_url('guru/profile/change_password') ?>" method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama *</label>
                            <input type="password" name="password_lama" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru *</label>
                            <input type="password" name="password_baru" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                            <input type="password" name="password_konfirmasi" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md transition">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
