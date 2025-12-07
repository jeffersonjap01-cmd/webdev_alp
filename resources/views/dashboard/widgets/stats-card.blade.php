@php
    $colorClasses = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'purple' => 'bg-purple-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'indigo' => 'bg-indigo-500',
    ];
    
    $changeColorClasses = [
        'increase' => 'text-green-600',
        'decrease' => 'text-red-600',
    ];
    
    $iconMap = [
        'users' => 'fas fa-users',
        'calendar-alt' => 'fas fa-calendar-alt',
        'chart-line' => 'fas fa-chart-line',
        'tasks' => 'fas fa-tasks',
        'dollar-sign' => 'fas fa-dollar-sign',
        'paw' => 'fas fa-paw',
        'user-md' => 'fas fa-user-md',
        'heartbeat' => 'fas fa-heartbeat',
    ];
@endphp

<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $colorClasses[$color] ?? 'bg-gray-500' }} rounded-md flex items-center justify-center">
                    <i class="{{ $iconMap[$icon] ?? 'fas fa-chart-bar' }} text-white"></i>
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">{{ $value }}</div>
                        @isset($change)
                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $changeColorClasses[$changeType] ?? 'text-gray-600' }}">
                                @if($changeType === 'increase')
                                    <i class="fas fa-arrow-up mr-1"></i>
                                @else
                                    <i class="fas fa-arrow-down mr-1"></i>
                                @endif
                                {{ $change }}
                            </div>
                        @endisset
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    @isset($footer)
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                {{ $footer }}
            </div>
        </div>
    @endisset
</div>