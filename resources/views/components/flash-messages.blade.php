@if (session('success'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-green-800">Berhasil</h3>
                    <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-green-50 rounded-md inline-flex text-green-400 hover:text-green-500 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-red-50 rounded-md inline-flex text-red-400 hover:text-red-500 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Peringatan</h3>
                    <p class="mt-1 text-sm text-yellow-700">{{ session('warning') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-yellow-50 rounded-md inline-flex text-yellow-400 hover:text-yellow-500 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                    <p class="mt-1 text-sm text-blue-700">{{ session('info') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-blue-50 rounded-md inline-flex text-blue-400 hover:text-blue-500 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Auto-hide flash messages after 5 seconds -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success messages
        const successAlerts = document.querySelectorAll('[class*="bg-green-50"]');
        successAlerts.forEach(alert => {
            setTimeout(() => {
                if (alert.style.display !== 'none') {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        });
        
        // Auto-hide info messages
        const infoAlerts = document.querySelectorAll('[class*="bg-blue-50"]');
        infoAlerts.forEach(alert => {
            setTimeout(() => {
                if (alert.style.display !== 'none') {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 7000);
        });
    });
</script>