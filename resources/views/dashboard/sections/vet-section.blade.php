<!-- Vet Dashboard Section -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Today's Schedule -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-calendar-day mr-2 text-gray-400"></i>
                Jadwal Hari Ini
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">09:00 - Max (Anjing)</p>
                            <p class="text-sm text-gray-500">Pemilik: Budi Santoso</p>
                        </div>
                    </div>
                    <span class="text-xs text-blue-600 font-medium">Scheduled</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">10:30 - Luna (Kucing)</p>
                            <p class="text-sm text-gray-500">Pemilik: Siti Sarah</p>
                        </div>
                    </div>
                    <span class="text-xs text-green-600 font-medium">Completed</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">14:00 - Rocky (Anjing)</p>
                            <p class="text-sm text-gray-500">Pemilik: Ahmad Wijaya</p>
                        </div>
                    </div>
                    <span class="text-xs text-yellow-600 font-medium">Upcoming</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('appointments') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Lihat Jadwal Lengkap →
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Statistics -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-heartbeat mr-2 text-gray-400"></i>
                Statistik Pasien
            </h3>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Pasien Bulan Ini</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">45</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Total Pasien</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">234</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Rekam Medis</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-600">189</dd>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">Resep Dikeluarkan</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600">156</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Recent Medical Records -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-file-medical mr-2 text-gray-400"></i>
                Rekam Medis Terbaru
            </h3>
            <div class="space-y-4">
                <div class="border-l-4 border-green-400 pl-4">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Max - Anjing</p>
                        <p class="text-sm text-gray-500">2 jam lalu</p>
                    </div>
                    <p class="text-sm text-gray-600">Pemeriksaan rutin, kondisi sehat</p>
                    <p class="text-xs text-gray-500">Pemilik: Budi Santoso</p>
                </div>
                <div class="border-l-4 border-blue-400 pl-4">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Luna - Kucing</p>
                        <p class="text-sm text-gray-500">1 hari lalu</p>
                    </div>
                    <p class="text-sm text-gray-600">Vaksinasi tahunan</p>
                    <p class="text-xs text-gray-500">Pemilik: Siti Sarah</p>
                </div>
                <div class="border-l-4 border-yellow-400 pl-4">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Rocky - Anjing</p>
                        <p class="text-sm text-gray-500">3 hari lalu</p>
                    </div>
                    <p class="text-sm text-gray-600">Pengobatan kulit, dermatitis</p>
                    <p class="text-xs text-gray-500">Pemilik: Ahmad Wijaya</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('medical-records.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Lihat Semua Rekam Medis →
                </a>
            </div>
        </div>
    </div>

    <!-- Upcoming Vaccinations -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-syringe mr-2 text-gray-400"></i>
                Jadwal Vaksinasi
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Max - Anjing</p>
                            <p class="text-sm text-gray-500">Vaksin Rabies</p>
                        </div>
                    </div>
                    <span class="text-xs text-red-600 font-medium">Terlewat</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Luna - Kucing</p>
                            <p class="text-sm text-gray-500">Vaksin FVRCP</p>
                        </div>
                    </div>
                    <span class="text-xs text-yellow-600 font-medium">Besok</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-blue-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Rocky - Anjing</p>
                            <p class="text-sm text-gray-500">Vaksin DHPP</p>
                        </div>
                    </div>
                    <span class="text-xs text-blue-600 font-medium">Minggu depan</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('vaccinations.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Kelola Semua Vaksinasi →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Vet Action Cards -->
<div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('pets.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-friends text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pasien Saya</dt>
                        <dd class="text-lg font-medium text-gray-900">234 Pasien</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('medical-records') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-medical text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Buat Rekam Medis</dt>
                        <dd class="text-lg font-medium text-gray-900">Input Baru</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('prescriptions') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-prescription text-2xl text-purple-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Resep Obat</dt>
                        <dd class="text-lg font-medium text-gray-900">Tulis Resep</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('vaccinations') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-syringe text-2xl text-red-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Jadwal Vaksin</dt>
                        <dd class="text-lg font-medium text-gray-900">Setup Jatah</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>
</div>