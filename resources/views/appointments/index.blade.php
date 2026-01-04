@extends('layouts.main')

@section('title', 'Appointments - VetCare')

@section('page-title', 'Appointments')
@section('page-description', 'Manage all pet appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
            <p class="mt-1 text-sm text-gray-600">
                Manage all pet appointments
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Add Appointment
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('appointments') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Declined</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="doctor" class="block text-sm font-medium text-gray-700">Dokter</label>
                    <select name="doctor" id="doctor" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Dokter</option>
                        @foreach(\App\Models\Doctor::active()->get() as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($appointments ?? [] as $appointment)
            <li>
                <a href="{{ route('appointments.show', $appointment) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $appointment->pet->name ?? 'Unknown Pet' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($appointment->status)
                                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                                    @case('accepted') bg-green-100 text-green-800 @break
                                                    @case('declined') bg-red-100 text-red-800 @break
                                                    @case('in_progress') bg-blue-100 text-blue-800 @break
                                                    @case('completed') bg-indigo-100 text-indigo-800 @break
                                                    @case('cancelled') bg-gray-100 text-gray-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                @switch($appointment->status)
                                                    @case('pending') Pending @break
                                                    @case('accepted') Accepted @break
                                                    @case('declined') Declined @break
                                                    @case('in_progress') In Progress @break
                                                    @case('completed') Completed @break
                                                    @case('cancelled') Cancelled @break
                                                    @default {{ ucfirst($appointment->status) }}
                                                @endswitch
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user-md mr-1.5"></i>
                                                {{ $appointment->doctor->name ?? 'No Doctor Assigned' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-clock mr-1.5"></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ optional($appointment->user)->name ?? 'Unknown User' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->pet->species ?? 'Unknown Species' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No appointments yet</p>
                <p class="text-gray-400 text-sm">Start by creating a new appointment</p>
                @if(in_array(auth()->user()->role, ['admin', 'customer']))
                <div class="mt-4">
                    <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Appointment
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($appointments) && $appointments->hasPages())
    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection