<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Presensi RFID</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Panel Guru</h1>
                <p class="text-sm text-gray-400 mt-1"><?php echo $this->session->userdata('name'); ?></p>
            </div>
            
            <nav class="mt-4">
                <a href="<?php echo base_url('guru/dashboard'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="<?php echo base_url('guru/jadwal'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'jadwal' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar mr-3"></i>Jadwal Saya
                </a>
                <a href="<?php echo base_url('guru/jurnal'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'jurnal' ? 'active' : ''; ?>">
                    <i class="fas fa-book mr-3"></i>Isi Jurnal
                </a>
                <a href="<?php echo base_url('guru/rekap_jurnal'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'rekap_jurnal' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt mr-3"></i>Rekap Jurnal
                </a>
                <?php 
                // Check if guru is wali kelas
                $this->load->model('Guru_model');
                $guru = $this->Guru_model->get_by_user_id($this->session->userdata('user_id'));
                if ($guru && $guru->is_wali_kelas):
                ?>
                <a href="<?php echo base_url('guru/wali_kelas'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'wali_kelas' ? 'active' : ''; ?>">
                    <i class="fas fa-users-class mr-3"></i>Wali Kelas
                </a>
                <?php endif; ?>
                <a href="<?php echo base_url('guru/profile'); ?>" 
                   class="sidebar-link <?php echo isset($active_menu) && $active_menu == 'profile' ? 'active' : ''; ?>">
                    <i class="fas fa-user mr-3"></i>Profile
                </a>
                <a href="<?php echo base_url('auth/logout'); ?>" 
                   class="sidebar-link"
                   onclick="return confirm('Yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">
                        <?php echo isset($title) ? $title : 'Dashboard'; ?>
                    </h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            <?php echo date('l, d F Y'); ?>
                        </span>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $this->session->flashdata('success'); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $this->session->flashdata('error'); ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
