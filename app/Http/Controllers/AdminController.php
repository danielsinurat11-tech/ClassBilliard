<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\TentangKami;
use App\Models\AboutFounder;
use App\Models\KeunggulanFasilitas;
use App\Models\PortfolioAchievement;
use App\Models\TimKami;
use App\Models\TestimoniPelanggan;
use App\Models\Event;
use App\Models\Footer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // Hero Section
    public function heroIndex()
    {
        $hero = HeroSection::first();
        return view('admin.manage-content.hero', compact('hero'));
    }

    public function heroUpdate(Request $request)
    {
        $hero = HeroSection::firstOrNew();
        
        if ($request->hasFile('logo_image')) {
            if ($hero->logo_image) {
                Storage::disk('public')->delete($hero->logo_image);
            }
            $hero->logo_image = $request->file('logo_image')->store('hero', 'public');
        }
        
        $hero->title = $request->title ?? 'CLASS';
        $hero->subtitle = $request->subtitle ?? 'BILLIARD';
        $hero->is_active = $request->has('is_active');
        $hero->save();

        return redirect()->route('admin.manage-content.hero')->with('success', 'Hero section updated successfully');
    }

    // Tentang Kami
    public function tentangKamiIndex()
    {
        $tentangKami = TentangKami::first();
        return view('admin.manage-content.tentang-kami', compact('tentangKami'));
    }

    public function tentangKamiUpdate(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'arah_gerak' => 'nullable|string',
            'is_active' => 'nullable',
            'video_url' => 'nullable|string|max:2048',
            'video_description' => 'nullable|string',
        ]);

        $tentangKami = TentangKami::firstOrNew();
        $tentangKami->fill($request->only(['title', 'subtitle']));

        if ($request->filled('video_url')) {
            $embed = $this->convertToEmbedUrl($request->input('video_url'));
            // hapus file lama jika sebelumnya pakai storage lokal
            if ($tentangKami->video_url && !preg_match('/^https?:\/\//', $tentangKami->video_url)) {
                Storage::disk('public')->delete($tentangKami->video_url);
            }
            $tentangKami->video_url = $embed;
        }

        if ($request->has('video_description')) {
            $tentangKami->video_description = $request->input('video_description');
        }

        $tentangKami->is_active = $request->has('is_active');
        $tentangKami->save();

        return redirect()->route('admin.manage-content.tentang-kami')->with('success', 'Tentang Kami updated successfully');
    }

    private function convertToEmbedUrl(?string $url): ?string
    {
        if (!$url) return null;
        $url = trim($url);
        $parsed = parse_url($url);
        if (!$parsed || empty($parsed['host'])) {
            return $url;
        }
        $host = $parsed['host'];
        $path = $parsed['path'] ?? '';
        $query = $parsed['query'] ?? '';

        if (strpos($host, 'youtu.be') !== false) {
            $id = ltrim($path, '/');
            $id = preg_replace('/[^A-Za-z0-9_-]/', '', $id);
            return $id ? "https://www.youtube.com/embed/{$id}" : $url;
        }

        if (strpos($host, 'youtube.com') !== false) {
            if (strpos($path, '/watch') === 0 || $path === '/watch') {
                parse_str($query, $params);
                $id = $params['v'] ?? null;
                $id = $id ? preg_replace('/[^A-Za-z0-9_-]/', '', $id) : null;
                return $id ? "https://www.youtube.com/embed/{$id}" : $url;
            }
            if (strpos($path, '/shorts/') === 0) {
                $id = trim(substr($path, strlen('/shorts/')));
                $id = preg_replace('/[^A-Za-z0-9_-]/', '', $id);
                return $id ? "https://www.youtube.com/embed/{$id}" : $url;
            }
            if (strpos($path, '/embed/') === 0) {
                return $url;
            }
        }

        // Instagram: support /p/{code}, /reel/{code}, /tv/{code}
        if (strpos($host, 'instagram.com') !== false) {
            $segments = array_values(array_filter(explode('/', $path)));
            if (count($segments) >= 2) {
                $type = $segments[0];
                $code = $segments[1];
                if (in_array($type, ['p', 'reel', 'tv'])) {
                    $code = preg_replace('/[^A-Za-z0-9_-]/', '', $code);
                    if (!empty($code)) {
                        return "https://www.instagram.com/{$type}/{$code}/embed";
                    }
                }
            }
            // If already embed
            if (strpos($path, '/embed') !== false) {
                return $url;
            }
        }

        return $url;
    }

    // About Founder
    public function aboutFounderIndex()
    {
        $aboutFounder = AboutFounder::first();
        return view('admin.manage-content.about-founder', compact('aboutFounder'));
    }

    public function aboutFounderUpdate(Request $request)
    {
        $aboutFounder = AboutFounder::firstOrNew();
        
        if ($request->hasFile('photo')) {
            if ($aboutFounder->photo) {
                Storage::disk('public')->delete($aboutFounder->photo);
            }
            $aboutFounder->photo = $request->file('photo')->store('founder', 'public');
        }
        
        $aboutFounder->fill($request->only(['title', 'subtitle', 'name', 'description', 'facebook_url', 'instagram_url', 'linkedin_url']));
        $aboutFounder->is_active = $request->has('is_active');
        $aboutFounder->save();

        return redirect()->route('admin.manage-content.about-founder')->with('success', 'About Founder updated successfully');
    }

    // Keunggulan Fasilitas
    public function keunggulanFasilitasIndex()
    {
        $keunggulanFasilitas = KeunggulanFasilitas::orderBy('order')->get();
        return view('admin.manage-content.keunggulan-fasilitas', compact('keunggulanFasilitas'));
    }

    public function keunggulanFasilitasStore(Request $request)
    {
        KeunggulanFasilitas::create($request->only(['title', 'subtitle', 'icon', 'name', 'description', 'order', 'is_active']));
        return redirect()->route('admin.manage-content.keunggulan-fasilitas')->with('success', 'Fasilitas added successfully');
    }

    public function keunggulanFasilitasUpdate(Request $request, $id)
    {
        $fasilitas = KeunggulanFasilitas::findOrFail($id);
        $fasilitas->fill($request->only(['title', 'subtitle', 'icon', 'name', 'description', 'order']));
        $fasilitas->is_active = $request->has('is_active');
        $fasilitas->save();

        return redirect()->route('admin.manage-content.keunggulan-fasilitas')->with('success', 'Fasilitas updated successfully');
    }

    public function keunggulanFasilitasDestroy($id)
    {
        KeunggulanFasilitas::findOrFail($id)->delete();
        return redirect()->route('admin.manage-content.keunggulan-fasilitas')->with('success', 'Fasilitas deleted successfully');
    }

    // Portfolio Achievement
    public function portfolioAchievementIndex()
    {
        $achievements = PortfolioAchievement::where('type', 'achievement')->orderBy('order')->get();
        $galleries = PortfolioAchievement::where('type', 'gallery')->orderBy('order')->get();
        return view('admin.manage-content.portfolio-achievement', compact('achievements', 'galleries'));
    }

    public function portfolioAchievementStore(Request $request)
    {
        $data = $request->only(['title', 'subtitle', 'type', 'icon', 'number', 'label', 'order', 'is_active']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('portfolio', 'public');
        }
        
        PortfolioAchievement::create($data);
        return redirect()->route('admin.manage-content.portfolio-achievement')->with('success', 'Item added successfully');
    }

    public function portfolioAchievementUpdate(Request $request, $id)
    {
        $item = PortfolioAchievement::findOrFail($id);
        
        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('portfolio', 'public');
        }
        
        $item->fill($request->only(['title', 'subtitle', 'type', 'icon', 'number', 'label', 'order']));
        $item->is_active = $request->has('is_active');
        $item->save();

        return redirect()->route('admin.manage-content.portfolio-achievement')->with('success', 'Item updated successfully');
    }

    public function portfolioAchievementDestroy($id)
    {
        $item = PortfolioAchievement::findOrFail($id);
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.manage-content.portfolio-achievement')->with('success', 'Item deleted successfully');
    }

    // Tim Kami
    public function timKamiIndex()
    {
        $timKami = TimKami::orderBy('order')->get();
        return view('admin.manage-content.tim-kami', compact('timKami'));
    }

    public function timKamiStore(Request $request)
    {
        $data = $request->only(['title', 'subtitle', 'name', 'position', 'facebook_url', 'instagram_url', 'linkedin_url', 'order', 'is_active']);
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }
        
        TimKami::create($data);
        return redirect()->route('admin.manage-content.tim-kami')->with('success', 'Team member added successfully');
    }

    public function timKamiUpdate(Request $request, $id)
    {
        $member = TimKami::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->photo = $request->file('photo')->store('team', 'public');
        }
        
        $member->fill($request->only(['title', 'subtitle', 'name', 'position', 'facebook_url', 'instagram_url', 'linkedin_url', 'order']));
        $member->is_active = $request->has('is_active');
        $member->save();

        return redirect()->route('admin.manage-content.tim-kami')->with('success', 'Team member updated successfully');
    }

    public function timKamiDestroy($id)
    {
        $member = TimKami::findOrFail($id);
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        $member->delete();
        return redirect()->route('admin.manage-content.tim-kami')->with('success', 'Team member deleted successfully');
    }

    // Testimoni Pelanggan
    public function testimoniPelangganIndex()
    {
        $testimonis = TestimoniPelanggan::orderBy('order')->get();
        return view('admin.manage-content.testimoni-pelanggan', compact('testimonis'));
    }

    public function testimoniPelangganStore(Request $request)
    {
        $data = $request->only(['title', 'subtitle', 'customer_name', 'customer_role', 'testimonial', 'rating', 'order', 'is_active']);
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimoni', 'public');
        }
        
        TestimoniPelanggan::create($data);
        return redirect()->route('admin.manage-content.testimoni-pelanggan')->with('success', 'Testimoni added successfully');
    }

    public function testimoniPelangganUpdate(Request $request, $id)
    {
        $testimoni = TestimoniPelanggan::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            if ($testimoni->photo) {
                Storage::disk('public')->delete($testimoni->photo);
            }
            $testimoni->photo = $request->file('photo')->store('testimoni', 'public');
        }
        
        $testimoni->fill($request->only(['title', 'subtitle', 'customer_name', 'customer_role', 'testimonial', 'rating', 'order']));
        $testimoni->is_active = $request->has('is_active');
        $testimoni->save();

        return redirect()->route('admin.manage-content.testimoni-pelanggan')->with('success', 'Testimoni updated successfully');
    }

    public function testimoniPelangganDestroy($id)
    {
        $testimoni = TestimoniPelanggan::findOrFail($id);
        if ($testimoni->photo) {
            Storage::disk('public')->delete($testimoni->photo);
        }
        $testimoni->delete();
        return redirect()->route('admin.manage-content.testimoni-pelanggan')->with('success', 'Testimoni deleted successfully');
    }

    // Event
    public function eventIndex()
    {
        $events = Event::orderBy('order')->get();
        return view('admin.manage-content.event', compact('events'));
    }

    public function eventStore(Request $request)
    {
        $data = $request->only(['title', 'subtitle', 'event_title', 'event_description', 'event_date', 'link_url', 'order', 'is_active']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }
        
        Event::create($data);
        return redirect()->route('admin.manage-content.event')->with('success', 'Event added successfully');
    }

    public function eventUpdate(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $request->file('image')->store('events', 'public');
        }
        
        $event->fill($request->only(['title', 'subtitle', 'event_title', 'event_description', 'event_date', 'link_url', 'order']));
        $event->is_active = $request->has('is_active');
        $event->save();

        return redirect()->route('admin.manage-content.event')->with('success', 'Event updated successfully');
    }

    public function eventDestroy($id)
    {
        $event = Event::findOrFail($id);
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return redirect()->route('admin.manage-content.event')->with('success', 'Event deleted successfully');
    }

    // Footer
    public function footerIndex()
    {
        $footer = Footer::first();
        return view('admin.manage-content.footer', compact('footer'));
    }

    public function footerUpdate(Request $request)
    {
        $footer = Footer::firstOrNew();
        $footer->fill($request->only([
            'about_text',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'address',
            'phone',
            'email',
            'google_maps_url',
            'monday_friday_hours',
            'saturday_sunday_hours'
        ]));
        $footer->is_active = $request->has('is_active');
        $footer->save();

        return redirect()->route('admin.manage-content.footer')->with('success', 'Footer updated successfully');
    }

    // Profile Management
    public function profileEdit()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function profilePassword(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password berhasil diperbarui.');
    }
}
