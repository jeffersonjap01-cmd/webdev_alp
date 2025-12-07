<!-- Owner Dashboard Section -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- My Pets Overview -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-paw mr-2 text-gray-400"></i>
                Hewan Peliharaan Saya
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1552053831-71594a27632d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Max">
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Max</p>
                            <p class="text-sm text-gray-500">Golden Retriever • 3 tahun</p>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Sehat
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="text-blue-600 hover:text-blue-500 text-sm">Lihat Detail</a>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1518791841217-8f162f1e1131?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Luna">
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Luna</p>
                            <p class="text-sm text-gray-500">Persian Cat • 2 tahun</p>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Perlu Perhatian
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="text-blue-600 hover:text-blue-500 text-sm">Lihat Detail</a>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('pets') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Kelola Semua Hewan →
                </a>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                Janji Temu Mendatang
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Dr. Ahmad Rizki</p>
                            <p class="text-sm text-gray-500">Max - Pemeriksaan Rutin</p>
                            <p class="text-xs text-gray-400">Besok, 14:00 WIB</p>
                        </div>
                    </div>
                    <span class="text-xs text-blue-600 font-medium">Confirmed</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-syringe text-yellow-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Dr. Sari Indrawati</p>
                            <p class="text-sm text-gray-500">Luna - Vaksinasi</p>
                            <p class="text-xs text-gray-400">15 Des 2025, 10:30 WIB</p>
                        </div>
                    </div>
                    <span class="text-xs text-yellow-600 font-medium">Pending</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('appointments') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Lihat Semua Janji →
                </a>
            </div>
        </div>
    </div>


</div>

<!-- Owner Action Cards -->
<div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('appointments') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-plus text-2xl text-blue-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Buat Janji Temu</dt>
                        <dd class="text-lg font-medium text-gray-900">Booking Online</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('pets') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-paw text-2xl text-green-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Tambah Hewan</dt>
                        <dd class="text-lg font-medium text-gray-900">Registrasi Baru</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('medical-records') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-medical text-2xl text-purple-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Riwayat Medis</dt>
                        <dd class="text-lg font-medium text-gray-900">Lihat Rekam</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>

    <a href="#" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-credit-card text-2xl text-indigo-600"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pembayaran</dt>
                        <dd class="text-lg font-medium text-gray-900">Tagihan & Invoice</dd>
                    </dl>
                </div>
            </div>
        </div>
    </a>
</div>
