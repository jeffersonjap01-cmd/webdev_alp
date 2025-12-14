@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden">
        @auth
            <!-- Logged In Dashboard Hero -->
            <div class="container mx-auto px-6 pt-12">
                <!-- Banner -->
                <div class="relative bg-gradient-to-r from-blue-400 to-blue-600 rounded-3xl shadow-xl overflow-hidden mb-12">
                     <div class="absolute inset-0">
                         <div class="absolute inset-0 bg-white opacity-10 pattern-dots"></div>
                          <!-- Decorative Circles -->
                        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-300 opacity-20 rounded-full blur-3xl"></div>
                    </div>
                
                    <div class="relative px-8 py-12 md:px-12 md:py-16 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div class="text-white z-10 w-full md:w-2/3">
                            <h1 class="text-3xl md:text-5xl font-bold mb-4 font-sans leading-tight">
                                Welcome back,<br> {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-blue-100 text-lg mb-8 max-w-xl">
                                @switch(auth()->user()->role)
                                    @case('admin')
                                        Manage your veterinary clinic efficiently. Overview of all activities.
                                        @break
                                    @case('doctor')
                                        Ready to save lives today? Check your appointments.
                                        @break
                                    @case('customer')
                                        Here's what's happening with your furry friends today.
                                        @break
                                @endswitch
                            </p>
                            
                            <div class="flex flex-wrap gap-4">
                                 @if(in_array(auth()->user()->role, ['customer', 'admin']))
                                    <a href="{{ route('appointments.create') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-xl font-bold shadow-md transition transform hover:-translate-y-1 flex items-center gap-2">
                                        <i class="fas fa-plus"></i> New Appointment
                                    </a>
                                    <a href="{{ route('pets.index') }}" class="bg-blue-500 bg-opacity-40 hover:bg-opacity-60 text-white border border-blue-300 px-6 py-3 rounded-xl font-bold transition flex items-center gap-2">
                                        <i class="fas fa-paw"></i> My Pets
                                    </a>
                                 @endif
                                  @if(auth()->user()->role === 'doctor')
                                     <a href="{{ route('appointments') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-xl font-bold shadow-md transition transform hover:-translate-y-1 flex items-center gap-2">
                                        <i class="fas fa-calendar-check"></i> My Schedule
                                    </a>
                                  @endif
                            </div>
                        </div>
                        <div class="hidden md:block w-1/3">
                             <img src="{{ asset('images/hero-dog.png') }}" class="w-full object-contain transform translate-y-4 filter drop-shadow-2xl" alt="Dashboard Dog">
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                     @if(auth()->user()->role === 'admin')
                        @include('dashboard.widgets.stats-card', ['title' => 'Total Customers', 'value' => number_format($stats['total_users'] ?? 0), 'icon' => 'users', 'color' => 'blue'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Total Pets', 'value' => number_format($stats['total_pets'] ?? 0), 'icon' => 'paw', 'color' => 'green'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Active Doctors', 'value' => number_format($stats['total_doctors'] ?? 0), 'icon' => 'user-md', 'color' => 'purple'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Today Appointments', 'value' => number_format($stats['today_appointments'] ?? 0), 'icon' => 'calendar-alt', 'color' => 'yellow'])
                    @elseif(auth()->user()->role === 'doctor')
                        @include('dashboard.widgets.stats-card', ['title' => 'Today Appointments', 'value' => number_format($stats['today_appointments'] ?? 0), 'icon' => 'calendar-day', 'color' => 'blue'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Total Patients', 'value' => number_format($stats['total_patients'] ?? 0), 'icon' => 'paw', 'color' => 'green'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Upcoming', 'value' => number_format($stats['upcoming_appointments'] ?? 0), 'icon' => 'calendar-alt', 'color' => 'purple'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Recent Records', 'value' => number_format($stats['recent_medical_records'] ?? 0), 'icon' => 'file-medical', 'color' => 'yellow'])
                    @elseif(auth()->user()->role === 'customer')
                        @include('dashboard.widgets.stats-card', ['title' => 'My Pets', 'value' => number_format($stats['total_pets'] ?? 0), 'icon' => 'paw', 'color' => 'blue'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Appointments', 'value' => number_format($stats['upcoming_appointments'] ?? 0), 'icon' => 'calendar-check', 'color' => 'green'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Vaccinations', 'value' => number_format($stats['upcoming_vaccinations'] ?? 0), 'icon' => 'syringe', 'color' => 'purple'])
                        @include('dashboard.widgets.stats-card', ['title' => 'Unpaid Bills', 'value' => number_format($stats['pending_payments'] ?? 0), 'icon' => 'credit-card', 'color' => 'yellow'])
                    @endif
                </div>

                @if(auth()->user()->role === 'doctor')
                     <div class="bg-white rounded-3xl shadow-sm border border-blue-50 p-8 mb-16">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-primary text-sm">
                                 <i class="fas fa-calendar-alt"></i>
                             </div>
                             Today's Appointments
                        </h3>
                        <div class="space-y-4">
                            @forelse($stats['upcoming_appointments_list'] ?? [] as $apt)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $apt->pet->name }} <span class="text-gray-500">({{ $apt->pet->user->name }})</span>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $apt->appointment_time->format('H:i') }} - {{ $apt->service_type }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                     @if($apt->status === 'in_progress')
                                        <a href="{{ route('doctor.examination.show', $apt->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                            Continue Exam
                                        </a>
                                    @elseif($apt->status === 'accepted' || $apt->status === 'scheduled')
                                        <a href="{{ route('doctor.examination.show', $apt->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                            Start Exam
                                        </a>
                                     @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($apt->status) }}
                                        </span>
                                     @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <div class="mb-2">
                                    <i class="fas fa-calendar-day text-4xl text-gray-300"></i>
                                </div>
                                <p>No appointments scheduled for today.</p>
                            </div>
                            @endforelse
                        </div>
                     </div>
                @endif

                 @if(auth()->user()->role === 'admin' && isset($stats['recent_activities']) && count($stats['recent_activities']) > 0)
                     <div class="bg-white rounded-3xl shadow-sm border border-blue-50 p-8 mb-16">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-primary text-sm">
                                 <i class="fas fa-history"></i>
                             </div>
                             Recent Activity
                        </h3>
                        <div class="space-y-4">
                             @foreach($stats['recent_activities'] as $activity)
                                <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                                     <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center text-{{ $activity['color'] }}-500">
                                         <i class="fas fa-{{ $activity['icon'] }}"></i>
                                     </div>
                                     <div class="flex-1">
                                         <p class="text-gray-800 font-medium text-sm">{!! $activity['message'] !!}</p>
                                         <p class="text-xs text-gray-400 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                                     </div>
                                </div>
                             @endforeach
                        </div>
                     </div>
                 @endif

            </div>
        @else
            <!-- Guest Hero (Landing Page) -->
            <div class="container mx-auto px-6 pt-12 pb-24 md:pt-20 md:pb-32">
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Text Content -->
                    <div class="w-full md:w-1/2 z-10">
                        <h1 class="text-4xl md:text-6xl font-bold text-gray-800 leading-tight mb-6">
                            Welcome to <br>
                            <span class="text-primary">VetCare</span>
                        </h1>
                        <p class="text-gray-600 text-lg mb-8 max-w-lg">
                            At VetCare we offer professional, reliable & trustworthy doggy daycare and boarding service through out the city.
                        </p>
                        <div class="flex gap-4">
                            <a href="#services" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Our Services <i class="fas fa-chevron-right ml-2 text-sm"></i>
                            </a>
                            <a href="#about" class="bg-white border-2 border-gray-200 text-gray-700 hover:border-primary hover:text-primary px-8 py-3 rounded-lg font-semibold transition">
                                About Us <i class="fas fa-chevron-right ml-2 text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Image Content -->
                    <div class="w-full md:w-1/2 relative mt-12 md:mt-0">
                        <!-- Blue Blob Background -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
                        <div class="absolute top-0 right-0 transform translate-x-10 -translate-y-10 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-2xl opacity-60"></div>
                        
                        <!-- Dog Image -->
                        <div class="relative z-10 flex justify-center">
                            <img src="{{ asset('images/hero-dog.png') }}" alt="Happy Golden Retriever" class="max-w-md w-full object-contain transform hover:scale-105 transition duration-500 drop-shadow-2xl">
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute top-10 right-10 z-0">
                            <i class="fas fa-paw text-blue-200 text-6xl transform rotate-12 opacity-50"></i>
                        </div>
                        <div class="absolute bottom-10 left-10 z-0">
                            <i class="fas fa-paw text-blue-200 text-4xl transform -rotate-12 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
                <svg class="relative block w-full h-[100px] text-blue-50" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-current"></path>
                </svg>
            </div>
        @endauth
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-blue-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2 relative">
                    <div class="absolute inset-0 bg-primary rounded-3xl transform rotate-3 scale-95 opacity-20"></div>
                    <img src="{{ asset('images/about-dog.png') }}" alt="About Us"
                        class="relative rounded-3xl shadow-xl w-full object-cover h-96 transform transition hover:-rotate-1 duration-300">

                    <!-- Floating Badge -->
                    <div
                        class="absolute -bottom-6 -right-6 bg-white p-4 rounded-2xl shadow-lg flex items-center gap-3 animate-bounce">
                        <div class="bg-blue-100 p-3 rounded-full text-primary">
                            <i class="fas fa-heart text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold">We Love</p>
                            <p class="text-sm font-bold text-gray-800">Your Pets</p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <h4 class="text-primary font-bold tracking-wider uppercase mb-2">Our Mission</h4>
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">What We Do</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Our Mission is to work with our clients to positively impact the quality of life for each dog that
                        we look after. We are always continually raising the standard of excellence for dog care and client
                        service.
                    </p>
                    <div class="space-y-4">
                        <p class="text-gray-600 leading-relaxed">
                            VetCare is more than just a service; it's a community of pet lovers dedicated to the well-being of your furry companions. 
                            Founded in 2025, our mission has always been to provide accessible, high-quality veterinary care ensuring every pet lives a long, happy life.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            We use state-of-the-art technology and modern medical practices, combined with a compassionate touch that treats every animal like our own family.
                        </p>
                    </div>

                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <span class="text-gray-700 font-medium">Professional Care</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <span class="text-gray-700 font-medium">Safe Environment</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <span class="text-gray-700 font-medium">Quality Time</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white relative">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-2 mb-2">
                <i class="fas fa-bone text-primary transform -rotate-45"></i>
                <span class="text-2xl font-handwriting text-primary font-bold">We Provide Best Services</span>
                <i class="fas fa-bone text-primary transform rotate-45"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-16">For Your Little Friends</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-blue-100 hover:border-primary hover:shadow-2xl transition duration-300 group text-left relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-10 -mt-10 transition group-hover:bg-primary group-hover:scale-150 duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-primary text-2xl mb-6 group-hover:bg-white group-hover:text-primary transition">
                            <i class="fas fa-dog"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Doggy Day Care</h3>
                        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                            Full day of fun and socialization for your dog. We ensure they have a great time while you are
                            away.
                        </p>
                        <a href="#"
                            class="text-primary font-semibold hover:tracking-wide transition-all text-sm flex items-center gap-1 group-hover:text-primary-dark">
                            Explore More <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-blue-100 hover:border-primary hover:shadow-2xl transition duration-300 group text-left relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-10 -mt-10 transition group-hover:bg-primary group-hover:scale-150 duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-primary text-2xl mb-6 group-hover:bg-white group-hover:text-primary transition">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Doggy Holidays</h3>
                        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                            Safe and comfortable boarding for your pets when you go on vacation. It's their holiday too!
                        </p>
                        <a href="#"
                            class="text-primary font-semibold hover:tracking-wide transition-all text-sm flex items-center gap-1 group-hover:text-primary-dark">
                            Explore More <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-blue-100 hover:border-primary hover:shadow-2xl transition duration-300 group text-left relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-10 -mt-10 transition group-hover:bg-primary group-hover:scale-150 duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-primary text-2xl mb-6 group-hover:bg-white group-hover:text-primary transition">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Health Checks</h3>
                        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                            Regular health checkups and vaccinations to keep your furry friends in top condition.
                        </p>
                        <a href="#"
                            class="text-primary font-semibold hover:tracking-wide transition-all text-sm flex items-center gap-1 group-hover:text-primary-dark">
                            Explore More <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex justify-center gap-4">
                <a href="#contact"
                    class="px-8 py-3 rounded-lg border-2 border-blue-200 text-primary font-bold hover:bg-blue-50 transition">Contact
                    Us</a>
                <a href="#services"
                    class="px-8 py-3 rounded-lg bg-primary text-white font-bold hover:bg-primary-dark transition shadow-lg">More
                    Services</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-blue-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Stat 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-primary mx-auto mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">25+</h3>
                    <p class="text-sm text-gray-500 mt-1">Years Experience</p>
                </div>
                <!-- Stat 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-primary mx-auto mb-4">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">30+</h3>
                    <p class="text-sm text-gray-500 mt-1">Care Takers</p>
                </div>
                <!-- Stat 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-primary mx-auto mb-4">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">500+</h3>
                    <p class="text-sm text-gray-500 mt-1">Positive Reviews</p>
                </div>
                <!-- Stat 4 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-primary mx-auto mb-4">
                        <i class="fas fa-dog"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">260+</h3>
                    <p class="text-sm text-gray-500 mt-1">Happy Pets</p>
                </div>
            </div>
        </div>
    </section>
@endsection