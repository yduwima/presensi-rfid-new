<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Admin - Presensi RFID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex-shrink-0">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-blue-600">Presensi RFID</h1>
                <p class="text-sm text-gray-600">Panel Admin</p>
            </div>
            
            <nav class="p-4 overflow-y-auto" style="height: calc(100vh - 88px);">
                <a href="<?php echo base_url('admin/dashboard'); ?>" 
                   class="sidebar-link <?php echo ($active_menu == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                
                <div class="mt-4">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Pengaturan</p>
                    <a href="<?php echo base_url('admin/settings'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'settings') ? 'active' : ''; ?>">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">Pengaturan Sekolah</span>
                    </a>
                    <a href="<?php echo base_url('admin/hari_kerja'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'hari_kerja') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-week w-5"></i>
                        <span class="ml-3">Hari Kerja</span>
                    </a>
                </div>
                
                <div class="mt-4">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Data Master</p>
                    <a href="<?php echo base_url('admin/tahun_ajaran'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'tahun_ajaran') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="ml-3">Tahun Ajaran</span>
                    </a>
                    <a href="<?php echo base_url('admin/semester'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'semester') ? 'active' : ''; ?>">
                        <i class="fas fa-book-open w-5"></i>
                        <span class="ml-3">Semester</span>
                    </a>
                    <a href="<?php echo base_url('admin/kelas'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'kelas') ? 'active' : ''; ?>">
                        <i class="fas fa-door-open w-5"></i>
                        <span class="ml-3">Kelas</span>
                    </a>
                    <a href="<?php echo base_url('admin/siswa'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'siswa') ? 'active' : ''; ?>">
                        <i class="fas fa-user-graduate w-5"></i>
                        <span class="ml-3">Data Siswa</span>
                    </a>
                    <a href="<?php echo base_url('admin/guru'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'guru') ? 'active' : ''; ?>">
                        <i class="fas fa-chalkboard-teacher w-5"></i>
                        <span class="ml-3">Data Guru</span>
                    </a>
                    <a href="<?php echo base_url('admin/naik_kelas'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'naik_kelas') ? 'active' : ''; ?>">
                        <i class="fas fa-level-up-alt w-5"></i>
                        <span class="ml-3">Naik Kelas</span>
                    </a>
                </div>
                
                <div class="mt-4">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Akademik</p>
                    <a href="<?php echo base_url('admin/mata_pelajaran'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'mata_pelajaran') ? 'active' : ''; ?>">
                        <i class="fas fa-book w-5"></i>
                        <span class="ml-3">Mata Pelajaran</span>
                    </a>
                    <a href="<?php echo base_url('admin/jadwal_pelajaran'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'jadwal_pelajaran') ? 'active' : ''; ?>">
                        <i class="fas fa-clock w-5"></i>
                        <span class="ml-3">Jadwal Pelajaran</span>
                    </a>
                    <a href="<?php echo base_url('admin/laporan/rekap_jurnal'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'jurnal') ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard w-5"></i>
                        <span class="ml-3">Rekap Jurnal</span>
                    </a>
                </div>
                
                <div class="mt-4">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Laporan</p>
                    <a href="<?php echo base_url('admin/laporan'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'laporan') ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-3">Laporan</span>
                    </a>
                </div>
                
                <div class="mt-4">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase">Sistem</p>
                    <a href="<?php echo base_url('admin/wa_settings'); ?>" 
                       class="sidebar-link <?php echo ($active_menu == 'wa_settings') ? 'active' : ''; ?>">
                        <i class="fab fa-whatsapp w-5"></i>
                        <span class="ml-3">Pengaturan WA</span>
                    </a>
                    <a href="<?php echo base_url('auth/logout'); ?>" 
                       class="sidebar-link text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800"><?php echo isset($title) ? $title : 'Dashboard'; ?></h2>
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700"><?php echo $this->session->userdata('username'); ?></p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($this->session->userdata('username'), 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo $this->session->flashdata('success'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo $this->session->flashdata('error'); ?></span>
                    </div>
                <?php endif; ?>
