<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">Selamat Datang, <?php echo $guru ? $guru->nama : 'Guru'; ?>!</h2>
            <p class="mt-2 text-blue-100">Hari ini: <?php echo $hari_ini; ?>, <?php echo date('d F Y'); ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-chalkboard-teacher text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
            Jadwal Mengajar Hari Ini
        </h3>
        <span class="badge badge-info"><?php echo count($jadwal_hari_ini); ?> Jadwal</span>
    </div>
    
    <?php if (!empty($jadwal_hari_ini)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($jadwal_hari_ini as $jadwal): ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-gray-800"><?php echo $jadwal->nama_mapel; ?></h4>
                        <span class="badge badge-success text-xs">
                            <?php echo substr($jadwal->jam_mulai, 0, 5); ?> - <?php echo substr($jadwal->jam_selesai, 0, 5); ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-users mr-1"></i><?php echo $jadwal->nama_kelas; ?>
                    </p>
                    <a href="<?php echo base_url('guru/jurnal/create?jadwal=' . $jadwal->id); ?>" 
                       class="mt-2 inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-book mr-1"></i>Isi Jurnal
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-8">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500">Tidak ada jadwal mengajar hari ini</p>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Jadwal Mengajar</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_jadwal; ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Jurnal Terisi Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $jurnal_bulan_ini; ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-book text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Kelas Diampu</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $kelas_diampu; ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Aksi Cepat
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo base_url('guru/jadwal'); ?>" 
           class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
            <i class="fas fa-calendar text-blue-600 text-2xl mb-2"></i>
            <span class="text-sm text-gray-700">Lihat Jadwal</span>
        </a>
        <a href="<?php echo base_url('guru/jurnal'); ?>" 
           class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <i class="fas fa-book text-green-600 text-2xl mb-2"></i>
            <span class="text-sm text-gray-700">Isi Jurnal</span>
        </a>
        <a href="<?php echo base_url('guru/rekap_jurnal'); ?>" 
           class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
            <i class="fas fa-file-alt text-purple-600 text-2xl mb-2"></i>
            <span class="text-sm text-gray-700">Rekap Jurnal</span>
        </a>
        <a href="<?php echo base_url('guru/profile'); ?>" 
           class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <i class="fas fa-user text-gray-600 text-2xl mb-2"></i>
            <span class="text-sm text-gray-700">Profile</span>
        </a>
    </div>
</div>
