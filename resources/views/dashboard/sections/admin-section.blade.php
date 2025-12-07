<!-- Admin Dashboard Section -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- System Overview -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2 text-gray-400"></i>
                Overview Sistem
            </h3>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Total Pemilik</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">156</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Total Hewan</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">298</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Dokter Aktif</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600">12</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Pending Activation</dt>
                    <dd class="mt-1 text-2xl font-semibold text-yellow-600">3</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Revenue Chart Placeholder -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-line mr-2 text-gray-400"></i>
                Pendapatan Bulanan
            </h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                    <p class="text-sm text-gray-500">Chart akan muncul setelah ada data</p>
                    <a href="{{ route('reports.dashboard') }}" class="text-blue-600 hover:text-blue-500 text-sm">
                        Lihat Laporan Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-user-plus mr-2 text-gray-400"></i>
                Pendaftaran Terbaru
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Siti+Sarah&color=7F9CF5&background=EBF4FF" alt="Siti Sarah">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Siti Sarah</p>
                            <p class="text-sm text-gray-500">Pemilik Hewan</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-400">2 jam lalu</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Dr+Ahmad&color=7F9CF5&background=EBF4FF" alt="Dr Ahmad">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Dr. Ahmad Rizki</p>
                            <p class="text-sm text-gray-500">Dokter Hewan</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-400">1 hari lalu</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Budi+Santoso&color=7F9CF5&background=EBF4FF" alt="Budi Santoso">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Budi Santoso</p>
                            <p class="text-sm text-gray-500">Pemilik Hewan</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-400">3 hari lalu</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('owners.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Lihat Semua Pemilik →
                </a>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-exclamation-triangle mr-2 text-gray-400"></i>
                Peringatan Sistem
            </h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-circle text-red-500 text-xs mt-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-900">3 dokter menunggu aktivasi</p>
                        <p class="text-xs text-gray-500">Perlu review dan persetujuan</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-circle text-yellow-500 text-xs mt-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-900">Backup terakhir: 2 hari lalu</p>
                        <p class="text-xs text-gray-500">Jadwal backup otomatis</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-circle text-green-500 text-xs mt-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-900">Sistem berjalan normal</p>
                        <p class="text-xs text-gray-500">Semua layanan berfungsi baik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Action Cards -->
<div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('owners.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Manajemen Pemilik</dt>
                        <dd class="text-lg font-medium text-gray-900">156 Akun</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('doctors.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-md text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Manajemen Dokter</dt>
                        <dd class="text-lg font-medium text-gray-900">15 Dokter</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('reports.dashboard') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-bar text-2xl text-purple-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Laporan & Analytics</dt>
                        <dd class="text-lg font-medium text-gray-900">Data Lengkap</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="#" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-cog text-2xl text-gray-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pengaturan Sistem</dt>
                        <dd class="text-lg font-medium text-gray-900">Konfigurasi</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>
</div>