<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - VetCare</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center">
                <i class="fas fa-paw text-4xl text-blue-600"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Daftar VetCare
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Buat akun baru untuk mengakses sistem
            </p>
        </div>

        <!-- Registration Form -->
        <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="text-base font-medium text-gray-900">Jenis Akun</label>
                <p class="text-sm leading-5 text-gray-500">Pilih jenis akun yang sesuai</p>
                <fieldset class="mt-4">
                    <legend class="sr-only">Jenis Akun</legend>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="customer" name="role" type="radio" value="customer" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('role') == 'customer' ? 'checked' : 'checked' }}>
                            <label for="customer" class="ml-3 block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-2 text-green-500"></i>
                                Customer
                                <p class="text-xs text-gray-500">Untuk pemilik hewan peliharaan</p>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="vet" name="role" type="radio" value="doctor" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('role') == 'doctor' ? 'checked' : '' }}>
                            <label for="vet" class="ml-3 block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-md mr-2 text-blue-500"></i>
                                Dokter Hewan
                                <p class="text-xs text-gray-500">Untuk praktisi dokter hewan</p>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="admin" name="role" type="radio" value="admin" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('role') == 'admin' ? 'checked' : '' }}>
                            <label for="admin" class="ml-3 block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-shield mr-2 text-red-500"></i>
                                Administrator
                                <p class="text-xs text-gray-500">Untuk administrator sistem</p>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Personal Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Informasi Pribadi</h3>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror"
                           placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                           placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Ulangi password">
                </div>
            </div>

            <!-- Additional Information for Customers -->
            <div id="customer-fields" class="space-y-4 hidden">
                <h3 class="text-lg font-medium text-gray-900">Informasi Tambahan</h3>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input id="phone" name="phone" type="text"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="address" name="address" rows="3"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    Saya setuju dengan
                    <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a>
                    serta
                    <a href="#" class="text-blue-600 hover:text-blue-500">kebijakan privasi</a>
                </label>
            </div>
            @error('terms')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Daftar Akun
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    // Show/hide customer fields based on role selection
    function toggleCustomerFields() {
        const customerFields = document.getElementById('customer-fields');
        const customerRadio = document.getElementById('customer');

        if (customerRadio.checked) {
            customerFields.classList.remove('hidden');
        } else {
            customerFields.classList.add('hidden');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleCustomerFields();

        // Add event listeners to role radios
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', toggleCustomerFields);
        });
    });
</script>
</body>
</html>