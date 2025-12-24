<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Mengajar Saya</h1>
        <p class="text-gray-600 mt-2">Jadwal mengajar untuk semester ini</p>
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

    <!-- Weekly Schedule Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Jadwal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($hari as $day): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= $day ?></div>
                                        <div class="text-sm text-gray-500">
                                            <?= count($jadwal_per_hari[$day]) ?> Kelas
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (empty($jadwal_per_hari[$day])): ?>
                                    <div class="text-gray-400 italic">Tidak ada jadwal</div>
                                <?php else: ?>
                                    <div class="space-y-2">
                                        <?php foreach ($jadwal_per_hari[$day] as $j): ?>
                                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?= $j->jam_mulai ?> - <?= $j->jam_selesai ?>
                                                        </span>
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            <?= $j->nama_mapel ?>
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        <i class="fas fa-users text-gray-400"></i>
                                                        Kelas <?= $j->nama_kelas ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <?php 
                                                    $today = date('l');
                                                    $day_map = [
                                                        'Monday' => 'Senin',
                                                        'Tuesday' => 'Selasa',
                                                        'Wednesday' => 'Rabu',
                                                        'Thursday' => 'Kamis',
                                                        'Friday' => 'Jumat',
                                                        'Saturday' => 'Sabtu'
                                                    ];
                                                    $today_indo = isset($day_map[$today]) ? $day_map[$today] : '';
                                                    ?>
                                                    <?php if ($day === $today_indo): ?>
                                                        <a href="<?= base_url('guru/jurnal/create/' . $j->id) ?>" 
                                                           class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md transition">
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Isi Jurnal
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Jadwal</p>
                    <p class="text-3xl font-bold"><?= count($jadwal) ?></p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Hari Mengajar</p>
                    <p class="text-3xl font-bold">
                        <?php 
                        $active_days = 0;
                        foreach ($jadwal_per_hari as $schedules) {
                            if (!empty($schedules)) $active_days++;
                        }
                        echo $active_days;
                        ?>
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-calendar-week text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Kelas Diampu</p>
                    <p class="text-3xl font-bold">
                        <?php 
                        $unique_classes = [];
                        foreach ($jadwal as $j) {
                            $unique_classes[$j->kelas_id] = true;
                        }
                        echo count($unique_classes);
                        ?>
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
