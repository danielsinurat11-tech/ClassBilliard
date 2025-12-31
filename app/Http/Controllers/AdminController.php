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
use App\Models\orders;
use App\Models\order_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Authorize only admin and super_admin for CMS content management
     * CMS routes are for managing website content (not core business operations)
     */
    private function authorizeAdminOnly(): void
    {
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized action.');
        }
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Sales Analytics page (Super Admin only)
     */
    public function salesAnalytics()
    {
        // Only super admin can access
        if (!auth()->user()->hasRole('super_admin') && auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.sales-analytics');
    }

    /**
     * Get menu sales data for chart (Super Admin only)
     */
    public function getMenuSalesData(Request $request)
    {
        // Only super admin can access this
        if (!auth()->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get date range from request (default: all time)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query untuk mendapatkan data penjualan per menu
        // Menggunakan orders yang sudah completed atau processing
        $query = order_items::select(
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'processing'])
            ->groupBy('order_items.menu_name')
            ->orderBy('total_quantity', 'desc');

        // Apply date filter if provided
        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        $menuSales = $query->get();

        // Format data untuk chart
        $chartData = [
            'labels' => $menuSales->pluck('menu_name')->toArray(),
            'quantities' => $menuSales->pluck('total_quantity')->map(function($q) {
                return (int)$q;
            })->toArray(),
            'revenues' => $menuSales->pluck('total_revenue')->map(function($r) {
                return (float)$r;
            })->toArray(),
            'total_items' => (int)$menuSales->sum('total_quantity'),
            'total_revenue' => (float)$menuSales->sum('total_revenue'),
            'menu_count' => $menuSales->count()
        ];

        return response()->json($chartData);
    }

    // Hero Section
    public function heroIndex()
    {
        $this->authorizeAdminOnly();
        $hero = HeroSection::first();
        return view('admin.manage-content.hero', compact('hero'));
    }

    public function heroUpdate(Request $request)
    {
        $this->authorizeAdminOnly();
        $hero = HeroSection::firstOrNew();
        
        if ($request->hasFile('logo_image')) {
            if ($hero->logo_image) {
                Storage::disk('public')->delete($hero->logo_image);
            }
            $hero->logo_image = $request->file('logo_image')->store('hero', 'public');
        }
        
        if ($request->hasFile('background_image')) {
            if ($hero->background_image) {
                Storage::disk('public')->delete($hero->background_image);
            }
            $hero->background_image = $request->file('background_image')->store('hero', 'public');
        }
        
        $hero->title = $request->title ?? 'The Art of';
        $hero->subtitle = $request->subtitle ?? 'Precision';
        $hero->tagline = $request->tagline ?? 'Premium Billiard Lounge & Bar';
        $hero->cta_text_1 = $request->cta_text_1 ?? 'BOOK A TABLE';
        $hero->cta_text_2 = $request->cta_text_2 ?? 'EXPLORE';
        $hero->is_active = $request->has('is_active');
        $hero->save();

        return redirect()->route('admin.cms.hero')->with('success', 'Hero section updated successfully');
    }

    // Tentang Kami
    public function tentangKamiIndex()
    {
        $this->authorizeAdminOnly();
        $tentangKami = TentangKami::first();
        return view('admin.manage-content.tentang-kami', compact('tentangKami'));
    }

    public function tentangKamiUpdate(Request $request)
    {
        $this->authorizeAdminOnly();
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
        
        if ($request->hasFile('image')) {
            if ($tentangKami->image) {
                Storage::disk('public')->delete($tentangKami->image);
            }
            $tentangKami->image = $request->file('image')->store('tentang-kami', 'public');
        }
        
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

        return redirect()->route('admin.cms.tentang-kami')->with('success', 'Tentang Kami updated successfully');
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
        $this->authorizeAdminOnly();
        $aboutFounder = AboutFounder::first();
        return view('admin.manage-content.about-founder', compact('aboutFounder'));
    }

    public function aboutFounderUpdate(Request $request)
    {
        $this->authorizeAdminOnly();
        $aboutFounder = AboutFounder::firstOrNew();
        
        if ($request->hasFile('photo')) {
            if ($aboutFounder->photo) {
                Storage::disk('public')->delete($aboutFounder->photo);
            }
            $aboutFounder->photo = $request->file('photo')->store('founder', 'public');
        }
        
        if ($request->hasFile('image')) {
            if ($aboutFounder->image) {
                Storage::disk('public')->delete($aboutFounder->image);
            }
            $aboutFounder->image = $request->file('image')->store('founder', 'public');
        }
        
        $aboutFounder->fill($request->only(['title', 'subtitle', 'name', 'position', 'quote', 'signature', 'facebook_url', 'instagram_url', 'linkedin_url']));
        $aboutFounder->is_active = $request->has('is_active');
        $aboutFounder->save();

        return redirect()->route('admin.cms.about-founder')->with('success', 'About Founder updated successfully');
    }

    // Keunggulan Fasilitas
    public function keunggulanFasilitasIndex()
    {
        $this->authorizeAdminOnly();
        $keunggulanFasilitas = KeunggulanFasilitas::orderBy('order')->get();
        return view('admin.manage-content.keunggulan-fasilitas', compact('keunggulanFasilitas'));
    }

    public function keunggulanFasilitasStore(Request $request)
    {
        $this->authorizeAdminOnly();
        $data = $request->only(['title', 'subtitle', 'icon', 'name', 'description', 'order']);
        $data['is_active'] = $request->has('is_active');
        KeunggulanFasilitas::create($data);
        return redirect()->route('admin.cms.keunggulan-fasilitas')->with('success', 'Fasilitas added successfully');
    }

    public function keunggulanFasilitasUpdate(Request $request, $id)
    {
        $this->authorizeAdminOnly();
        $fasilitas = KeunggulanFasilitas::findOrFail($id);
        $fasilitas->fill($request->only(['title', 'subtitle', 'icon', 'name', 'description', 'order']));
        $fasilitas->is_active = $request->has('is_active');
        $fasilitas->save();

        return redirect()->route('admin.cms.keunggulan-fasilitas')->with('success', 'Fasilitas updated successfully');
    }

    public function keunggulanFasilitasDestroy($id)
    {
        $this->authorizeAdminOnly();
        KeunggulanFasilitas::findOrFail($id)->delete();
        return redirect()->route('admin.cms.keunggulan-fasilitas')->with('success', 'Fasilitas deleted successfully');
    }

    // Portfolio Achievement
    public function portfolioAchievementIndex()
    {
        $this->authorizeAdminOnly();
        // Semua achievements ditampilkan di dashboard tanpa filter type
        $allAchievements = PortfolioAchievement::orderBy('order')->get();
        return view('admin.manage-content.portfolio-achievement', compact('allAchievements'));
    }

    public function portfolioAchievementStore(Request $request)
    {
        $this->authorizeAdminOnly();
        $data = $request->only(['title', 'description', 'order', 'type', 'subtitle', 'icon', 'number', 'label']);
        $data['is_active'] = $request->has('is_active');
        
        // Set type ke gallery karena dashboard hanya menampilkan items dengan image
        $data['type'] = 'gallery';
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('portfolio', 'public');
        }
        
        PortfolioAchievement::create($data);
        return redirect()->route('admin.cms.portfolio-achievement')->with('success', 'Item added successfully');
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
        
        $data = $request->only(['title', 'description', 'order', 'type', 'subtitle', 'icon', 'number', 'label']);
        
        // Pastikan type tetap gallery
        $data['type'] = 'gallery';
        
        $item->fill($data);
        $item->is_active = $request->has('is_active');
        $item->save();

        return redirect()->route('admin.cms.portfolio-achievement')->with('success', 'Item updated successfully');
    }

    public function portfolioAchievementDestroy($id)
    {
        $item = PortfolioAchievement::findOrFail($id);
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.cms.portfolio-achievement')->with('success', 'Item deleted successfully');
    }

    // Tim Kami
    public function timKamiIndex()
    {
        $this->authorizeAdminOnly();
        $timKami = TimKami::orderBy('order')->get();
        return view('admin.manage-content.tim-kami', compact('timKami'));
    }

    public function timKamiStore(Request $request)
    {
        $this->authorizeAdminOnly();
        $data = $request->only(['title', 'subtitle', 'name', 'position', 'bio', 'facebook_url', 'instagram_url', 'linkedin_url', 'order']);
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('team', 'public');
        }
        
        TimKami::create($data);
        return redirect()->route('admin.cms.tim-kami')->with('success', 'Team member added successfully');
    }

    public function timKamiUpdate(Request $request, $id)
    {
        $this->authorizeAdminOnly();
        $member = TimKami::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->photo = $request->file('photo')->store('team', 'public');
        }
        
        if ($request->hasFile('image')) {
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $member->image = $request->file('image')->store('team', 'public');
        }
        
        $member->fill($request->only(['title', 'subtitle', 'name', 'position', 'bio', 'facebook_url', 'instagram_url', 'linkedin_url', 'order']));
        $member->is_active = $request->has('is_active');
        $member->save();

        return redirect()->route('admin.cms.tim-kami')->with('success', 'Team member updated successfully');
    }

    public function timKamiDestroy($id)
    {
        $this->authorizeAdminOnly();
        $member = TimKami::findOrFail($id);
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        $member->delete();
        return redirect()->route('admin.cms.tim-kami')->with('success', 'Team member deleted successfully');
    }

    // Testimoni Pelanggan
    public function testimoniPelangganIndex()
    {
        $this->authorizeAdminOnly();
        $testimonis = TestimoniPelanggan::orderBy('order')->get();
        return view('admin.manage-content.testimoni-pelanggan', compact('testimonis'));
    }

    public function testimoniPelangganStore(Request $request)
    {
        $this->authorizeAdminOnly();
        $data = $request->only(['title', 'subtitle', 'customer_name', 'name', 'customer_role', 'role', 'testimonial', 'rating', 'order']);
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimoni', 'public');
        }
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimoni', 'public');
        }
        
        TestimoniPelanggan::create($data);
        return redirect()->route('admin.cms.testimoni-pelanggan')->with('success', 'Testimoni added successfully');
    }

    public function testimoniPelangganUpdate(Request $request, $id)
    {
        $this->authorizeAdminOnly();
        $testimoni = TestimoniPelanggan::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            if ($testimoni->photo) {
                Storage::disk('public')->delete($testimoni->photo);
            }
            $testimoni->photo = $request->file('photo')->store('testimoni', 'public');
        }
        
        if ($request->hasFile('image')) {
            if ($testimoni->image) {
                Storage::disk('public')->delete($testimoni->image);
            }
            $testimoni->image = $request->file('image')->store('testimoni', 'public');
        }
        
        $testimoni->fill($request->only(['title', 'subtitle', 'customer_name', 'name', 'customer_role', 'role', 'testimonial', 'rating', 'order']));
        $testimoni->is_active = $request->has('is_active');
        $testimoni->save();

        return redirect()->route('admin.cms.testimoni-pelanggan')->with('success', 'Testimoni updated successfully');
    }

    public function testimoniPelangganDestroy($id)
    {
        $this->authorizeAdminOnly();
        $testimoni = TestimoniPelanggan::findOrFail($id);
        if ($testimoni->photo) {
            Storage::disk('public')->delete($testimoni->photo);
        }
        $testimoni->delete();
        return redirect()->route('admin.cms.testimoni-pelanggan')->with('success', 'Testimoni deleted successfully');
    }

    // Event
    public function eventIndex()
    {
        $this->authorizeAdminOnly();
        $events = Event::orderBy('order')->get();
        return view('admin.manage-content.event', compact('events'));
    }

    public function eventStore(Request $request)
    {
        $this->authorizeAdminOnly();
        $data = $request->only(['title', 'subtitle', 'event_title', 'event_description', 'description', 'category', 'event_date', 'link_url', 'order']);
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }
        
        Event::create($data);
        return redirect()->route('admin.cms.event')->with('success', 'Event added successfully');
    }

    public function eventUpdate(Request $request, $id)
    {
        $this->authorizeAdminOnly();
        $event = Event::findOrFail($id);
        
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $request->file('image')->store('events', 'public');
        }
        
        $event->fill($request->only(['title', 'subtitle', 'event_title', 'event_description', 'description', 'category', 'event_date', 'link_url', 'order']));
        $event->is_active = $request->has('is_active');
        $event->save();

        return redirect()->route('admin.cms.event')->with('success', 'Event updated successfully');
    }

    public function eventDestroy($id)
    {
        $this->authorizeAdminOnly();
        $event = Event::findOrFail($id);
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return redirect()->route('admin.cms.event')->with('success', 'Event deleted successfully');
    }

    // Footer
    public function footerIndex()
    {
        $this->authorizeAdminOnly();
        $footer = Footer::first();
        return view('admin.manage-content.footer', compact('footer'));
    }

    public function footerUpdate(Request $request)
    {
        $this->authorizeAdminOnly();
        $footer = Footer::firstOrNew();
        $footer->fill($request->only([
            'about_text',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'whatsapp',
            'address',
            'location_name',
            'phone',
            'email',
            'google_maps_url',
            'map_url',
            'monday_friday_hours',
            'saturday_sunday_hours',
            'opening_hours',
            'copyright'
        ]));
        $footer->is_active = $request->has('is_active');
        $footer->save();

        return redirect()->route('admin.cms.footer')->with('success', 'Footer updated successfully');
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

    public function profileColorUpdate(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'primary_color' => ['required', 'string', 'in:#fbbf24,#fa9a08,#2f7d7a'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Warna preferensi berhasil diperbarui.');
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

