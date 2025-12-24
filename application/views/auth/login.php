<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Presensi RFID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <?php if (isset($settings) && $settings && $settings->logo && file_exists('./assets/uploads/' . $settings->logo)): ?>
                    <div class="mb-4 flex justify-center">
                        <img src="<?php echo base_url('assets/uploads/' . $settings->logo); ?>" 
                             alt="Logo Sekolah" 
                             class="h-24 object-contain">
                    </div>
                <?php else: ?>
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                <?php endif; ?>
                <h1 class="text-2xl font-bold text-gray-800">
                    <?php echo (isset($settings) && $settings && $settings->nama_sekolah) ? $settings->nama_sekolah : 'Presensi RFID'; ?>
                </h1>
                <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo $this->session->flashdata('error'); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo $this->session->flashdata('success'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?php echo base_url('auth/login'); ?>" method="POST" class="space-y-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" id="username" name="username" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan username"
                            value="<?php echo set_value('username'); ?>">
                    </div>
                    <?php echo form_error('username', '<p class="text-red-500 text-xs mt-1">', '</p>'); ?>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan password">
                    </div>
                    <?php echo form_error('password', '<p class="text-red-500 text-xs mt-1">', '</p>'); ?>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                    Login
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Default login: admin / admin</p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-6 text-white text-sm">
            &copy; <?php echo date('Y'); ?> <?php echo (isset($settings) && $settings && $settings->nama_sekolah) ? $settings->nama_sekolah : 'Presensi RFID'; ?>. All rights reserved.
        </div>
    </div>

    <script>
        // Auto hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-red-100, .bg-green-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
