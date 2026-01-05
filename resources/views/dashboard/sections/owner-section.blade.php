<!-- Customer Dashboard Section -->
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
                <a href="{{ route('pets.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
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

    <!-- Medical Records Section -->
    <div class="bg-white overflow-hidden shadow rounded-lg lg:col-span-2">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-file-medical mr-2 text-gray-400"></i>
                    Rekam Medis Saya
                </h3>
                <a href="{{ route('medical-records') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                    Lihat Semua
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                $medicalRecords = \App\Models\MedicalRecord::with(['pet', 'doctor', 'appointment', 'diagnoses'])
                    ->whereHas('pet', function($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->take(10)
                    ->get()
                    ->filter(function($record) {
                        // Filter only paid medical records for customer
                        $invoice = null;
                        if($record->appointment) {
                            $invoice = \App\Models\Invoice::where('appointment_id', $record->appointment->id)->first();
                        }
                        // Show if no invoice (old records) or if paid
                        return !$invoice || $invoice->status === 'paid';
                    })
                    ->take(3);
            @endphp

            @if($medicalRecords->count() > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($medicalRecords as $record)
                @php
                    $invoice = null;
                    if($record->appointment) {
                        $invoice = \App\Models\Invoice::where('appointment_id', $record->appointment->id)->first();
                    }
                    $isPaid = !$invoice || $invoice->status === 'paid';
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-file-medical text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $record->pet->name ?? 'Unknown Pet' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($invoice && $isPaid)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Lunas
                        </span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">
                            <i class="fas fa-user-md mr-1"></i>
                            {{ optional($record->doctor)->name ?? 'Unknown Doctor' }}
                        </p>
                        @if($record->diagnoses && $record->diagnoses->count() > 0)
                        <p class="text-xs text-gray-600 mt-1">
                            <i class="fas fa-stethoscope mr-1"></i>
                            {{ $record->diagnoses->first()->diagnosis_name ?? 'No Diagnosis' }}
                        </p>
                        @endif
                    </div>
                    <a href="{{ route('medical-records.show', $record) }}" class="block w-full text-center px-3 py-2 border border-blue-600 rounded-md text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i>
                        Lihat Detail
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-file-medical text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada rekam medis yang dapat diakses</p>
                <p class="text-gray-400 text-sm mt-1">Rekam medis akan muncul di sini setelah pembayaran selesai</p>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Customer Action Cards -->
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

    <a href="{{ route('pets.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
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
