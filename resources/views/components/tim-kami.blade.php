{{-- Tim Kami Section --}}
@php
    $timKami = \App\Models\TimKami::where('is_active', true)->orderBy('order')->get();
@endphp
@if($timKami->count() > 0)
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Tim Kami</h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">Tim profesional yang siap melayani Anda dengan sepenuh hati</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($timKami as $member)
            <div class="bg-[#1a1a1a] p-6 rounded-xl border border-[#fa9a08]/20 text-center hover:border-[#fa9a08] transition-all duration-300">
                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-[#2a2a2a] border-4 border-[#fa9a08] overflow-hidden">
                    <img src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/logo.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold text-white mb-2">{{ $member->name }}</h3>
                <p class="text-[#fa9a08] mb-4">{{ $member->position }}</p>
                <div class="flex justify-center gap-3">
                    @if($member->facebook_url)
                    <a href="{{ $member->facebook_url }}" target="_blank" class="text-gray-400 hover:text-[#fa9a08] transition-colors">
                        <i class="ri-facebook-fill text-xl"></i>
                    </a>
                    @endif
                    @if($member->instagram_url)
                    <a href="{{ $member->instagram_url }}" target="_blank" class="text-gray-400 hover:text-[#fa9a08] transition-colors">
                        <i class="ri-instagram-fill text-xl"></i>
                    </a>
                    @endif
                    @if($member->linkedin_url)
                    <a href="{{ $member->linkedin_url }}" target="_blank" class="text-gray-400 hover:text-[#fa9a08] transition-colors">
                        <i class="ri-linkedin-fill text-xl"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- Tim Kami Section --}}
