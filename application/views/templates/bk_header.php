<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Panel BK'; ?> - Sistem Presensi RFID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-purple-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Panel BK</h1>
                <p class="text-sm text-purple-200">Bimbingan Konseling</p>
            </div>
            <nav class="mt-6">
                <a href="<?php echo base_url('bk/dashboard'); ?>" class="flex items-center px-4 py-3 text-white hover:bg-purple-700 <?php echo ($this->uri->segment(2) == 'dashboard' || !$this->uri->segment(2)) ? 'bg-purple-700' : ''; ?>">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="<?php echo base_url('bk/monitoring'); ?>" class="flex items-center px-4 py-3 text-white hover:bg-purple-700 <?php echo ($this->uri->segment(2) == 'monitoring') ? 'bg-purple-700' : ''; ?>">
                    <i class="fas fa-chart-line mr-3"></i>
                    Monitoring BK
                </a>
                <a href="<?php echo base_url('bk/surat'); ?>" class="flex items-center px-4 py-3 text-white hover:bg-purple-700 <?php echo ($this->uri->segment(2) == 'surat') ? 'bg-purple-700' : ''; ?>">
                    <i class="fas fa-file-alt mr-3"></i>
                    Cetak Surat
                </a>
                <a href="<?php echo base_url('auth/logout'); ?>" class="flex items-center px-4 py-3 text-white hover:bg-purple-700">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800"><?php echo isset($title) ? $title : 'Dashboard'; ?></h2>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-2">BK Panel</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php if($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $this->session->flashdata('success'); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $this->session->flashdata('error'); ?></span>
                </div>
                <?php endif; ?>
