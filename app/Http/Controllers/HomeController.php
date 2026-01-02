<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\TentangKami;
use App\Models\AboutFounder;
use App\Models\PortfolioAchievement;
use App\Models\TimKami;
use App\Models\TestimoniPelanggan;
use App\Models\Event;
use App\Models\Footer;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $hero = HeroSection::where('is_active', true)->first();
        $about = TentangKami::where('is_active', true)->first();
        $founder = AboutFounder::where('is_active', true)->first();
        $achievements = PortfolioAchievement::where('is_active', true)->orderBy('order')->get();
        $teamMembers = TimKami::where('is_active', true)->orderBy('order')->get();
        $testimonials = TestimoniPelanggan::where('is_active', true)->orderBy('order')->limit(4)->get();
        $events = Event::where('is_active', true)->orderBy('event_date')->get();
        $footer = Footer::where('is_active', true)->first();
        $contact = Contact::where('is_active', true)->first();

        return view('home', compact(
            'hero',
            'about',
            'founder',
            'achievements',
            'teamMembers',
            'testimonials',
            'events',
            'footer',
            'contact'
        ));
    }

    public function submitTestimonial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'testimonial' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->to(route('home') . '#testimonials')
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'customer_name' => $request->name,
            'name' => $request->name,
            'customer_role' => $request->role ?? 'Customer',
            'role' => $request->role ?? 'Customer',
            'rating' => $request->rating,
            'testimonial' => $request->testimonial,
            'is_active' => true, // Langsung aktif, bisa di-nonaktifkan atau dihapus oleh admin
            'order' => 0,
        ];

        TestimoniPelanggan::create($data);

        return redirect()->to(route('home') . '#testimonials')
            ->with('testimonial_success', 'Terima kasih! Testimoni Anda telah berhasil dikirim dan akan ditampilkan di website.');
    }
}
