@extends('tenant-website.layouts.tenant')

@section('title', __('Welcome'))

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-montessori-blue via-montessori-green to-montessori-yellow py-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
            <div class="absolute bottom-20 right-20 w-48 h-48 bg-white rounded-full"></div>
            <div class="absolute top-40 right-40 w-24 h-24 bg-white rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-display font-bold text-white mb-6 drop-shadow-lg">
                    Welcome to {{ tenant('name') ?? config('app.name') }}
                </h1>
                <p class="text-xl md:text-2xl text-white/95 mb-8 max-w-3xl mx-auto font-light">
                    Where every child's unique journey of discovery begins
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#contact" class="inline-flex items-center px-8 py-3 border-2 border-white text-base font-semibold rounded-full text-white bg-white/10 hover:bg-white hover:text-primary-600 transition backdrop-blur-sm">
                        Schedule a Visit
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#programs" class="inline-flex items-center px-8 py-3 border-2 border-white text-base font-semibold rounded-full text-white hover:bg-white/10 transition backdrop-blur-sm">
                        Explore Programs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Philosophy Section -->
    <div id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">The Montessori Approach</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Empowering children to become independent, confident learners through hands-on discovery
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto mb-6 bg-montessori-pink/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-heart-fill text-4xl text-montessori-pink"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Child-Centered Learning</h3>
                    <p class="text-gray-600">Each child learns at their own pace in a prepared environment that encourages exploration and independence.</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto mb-6 bg-montessori-green/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-hand-thumbs-up-fill text-4xl text-montessori-green"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Hands-On Materials</h3>
                    <p class="text-gray-600">Specially designed learning materials that engage the senses and make abstract concepts concrete.</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto mb-6 bg-montessori-blue/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="bi bi-people-fill text-4xl text-montessori-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mixed-Age Groups</h3>
                    <p class="text-gray-600">Children learn from and with each other, fostering collaboration, empathy, and leadership.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs Section -->
    <div id="programs" class="py-20 bg-montessori-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">Our Programs</h2>
                <p class="text-xl text-gray-600">Tailored learning experiences for every stage of development</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Infant Community -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="h-3 bg-gradient-to-r from-montessori-pink to-primary-300"></div>
                    <div class="p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-montessori-pink/20 rounded-xl flex items-center justify-center">
                                <i class="bi bi-emoji-smile text-2xl text-montessori-pink"></i>
                            </div>
                            <h3 class="text-2xl font-display font-bold text-gray-900 ms-3">Infant Community</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Ages 3 months - 18 months</p>
                        <p class="text-gray-700">A nurturing environment where the youngest learners develop trust, movement, and communication in a safe, stimulating space.</p>
                    </div>
                </div>
                <!-- Toddler Community -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="h-3 bg-gradient-to-r from-montessori-yellow to-primary-400"></div>
                    <div class="p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-montessori-yellow/20 rounded-xl flex items-center justify-center">
                                <i class="bi bi-star-fill text-2xl text-montessori-yellow"></i>
                            </div>
                            <h3 class="text-2xl font-display font-bold text-gray-900 ms-3">Toddler Community</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Ages 18 months - 3 years</p>
                        <p class="text-gray-700">Active exploration and developing independence through practical life activities and social interaction.</p>
                    </div>
                </div>
                <!-- Primary Program -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="h-3 bg-gradient-to-r from-montessori-green to-montessori-blue"></div>
                    <div class="p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-montessori-green/20 rounded-xl flex items-center justify-center">
                                <i class="bi bi-book-fill text-2xl text-montessori-green"></i>
                            </div>
                            <h3 class="text-2xl font-display font-bold text-gray-900 ms-3">Primary Program</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Ages 3 - 6 years</p>
                        <p class="text-gray-700">A rich curriculum covering practical life, sensorial, language, mathematics, and cultural subjects through self-directed activity.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">What Parents Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-montessori-cream rounded-2xl p-8 border-l-4 border-montessori-blue">
                    <div class="flex mb-4">
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                    </div>
                    <p class="text-gray-700 mb-4 italic">"The transformation in our daughter has been remarkable. She's become so independent and curious about the world around her. The teachers truly understand each child's needs."</p>
                    <p class="font-semibold text-gray-900">— Sarah M., Parent</p>
                </div>
                <div class="bg-montessori-cream rounded-2xl p-8 border-l-4 border-montessori-green">
                    <div class="flex mb-4">
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                        <i class="bi bi-star-fill text-montessori-yellow"></i>
                    </div>
                    <p class="text-gray-700 mb-4 italic">"We couldn't be happier with our choice. The Montessori approach has helped our son develop confidence, critical thinking skills, and a genuine love for learning."</p>
                    <p class="font-semibold text-gray-900">— James L., Parent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="py-20 bg-gradient-to-br from-primary-600 to-primary-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-display font-bold text-white mb-6">Begin Your Child's Journey</h2>
            <p class="text-xl text-white/90 mb-8">
                Schedule a tour to see our classrooms in action and meet our dedicated educators
            </p>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-2">Your Name</label>
                        <input type="text" class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="Enter your name">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-2">Email Address</label>
                        <input type="email" class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-2">Phone Number</label>
                        <input type="tel" class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="(555) 123-4567">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-2">Child's Age</label>
                        <input type="text" class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="e.g., 3 years">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-white mb-2">Message (Optional)</label>
                        <textarea rows="4" class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="Tell us about your interests or questions..."></textarea>
                    </div>
                </div>
                <button class="mt-6 w-full md:w-auto px-12 py-4 bg-white text-primary-600 font-bold rounded-full hover:bg-montessori-cream transition-all shadow-lg hover:shadow-xl">
                    Request Information
                </button>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush
@endsection
