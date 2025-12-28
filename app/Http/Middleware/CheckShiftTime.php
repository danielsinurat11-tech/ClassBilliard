<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckShiftTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is authenticated and has shift_id
        if (!$user || !$user->shift_id) {
            return $next($request);
        }

        $shift = $user->shift;

        // If shift doesn't exist or not active, allow access
        if (!$shift || !$shift->is_active) {
            return $next($request);
        }

        // Get current time
        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        // Parse shift times
        $startTime = $this->parseTime($shift->start_time, $today);
        $endTime = $this->parseTime($shift->end_time, $today);

        // Handle midnight crossing (e.g., 22:00 - 06:00)
        if ($endTime < $startTime) {
            // Shift crosses midnight
            if ($now < $startTime) {
                $startTime = $startTime->copy()->subDay();
            } else {
                $endTime = $endTime->copy()->addDay();
            }
        }

        // Add 30 minutes tolerance before and after
        $toleranceStart = $startTime->copy()->subMinutes(30);
        $toleranceEnd = $endTime->copy()->addMinutes(30);

        // Update shift_end di session jika belum ada atau perlu update
        if (!session('shift_end') || !session('shift_end_datetime')) {
            session(['shift_end' => $endTime->timestamp]);
            session(['shift_end_datetime' => $endTime->toIso8601String()]);
        }
        
        // Transfer order dari shift sebelumnya yang belum selesai ke shift aktif saat ini
        $activeShift = \App\Models\Shift::getActiveShift();
        if ($activeShift) {
            // Cari semua shift yang bukan shift aktif saat ini
            $previousShifts = \App\Models\Shift::where('is_active', true)
                ->where('id', '!=', $activeShift->id)
                ->get();
            
            // Transfer order yang belum selesai dari shift sebelumnya ke shift aktif
            foreach ($previousShifts as $prevShift) {
                $pendingOrders = \App\Models\orders::where('shift_id', $prevShift->id)
                    ->whereIn('status', ['pending', 'processing'])
                    ->get();
                
                if ($pendingOrders->count() > 0) {
                    try {
                        \Illuminate\Support\Facades\DB::beginTransaction();
                        foreach ($pendingOrders as $order) {
                            $order->shift_id = $activeShift->id;
                            $order->save();
                        }
                        \Illuminate\Support\Facades\DB::commit();
                        
                        \Illuminate\Support\Facades\Log::info('Orders transferred from previous shift to active shift (middleware)', [
                            'from_shift' => $prevShift->id,
                            'to_shift' => $activeShift->id,
                            'order_count' => $pendingOrders->count()
                        ]);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\DB::rollBack();
                        \Illuminate\Support\Facades\Log::error('Error transferring orders in middleware: ' . $e->getMessage());
                    }
                }
            }
        }
        
        // Check if current time is within tolerance range
        if ($now < $toleranceStart || $now > $toleranceEnd) {
            // Outside working hours - FORCE LOGOUT
            $shiftName = $shift->name;
            $startTimeFormatted = $shift->start_time;
            $endTimeFormatted = $shift->end_time;

            // Transfer pending orders to next shift before logout
            $nextShift = \App\Models\Shift::getNextShift();
            if ($nextShift) {
                $pendingOrders = \App\Models\orders::where('shift_id', $user->shift_id)
                    ->whereIn('status', ['pending', 'processing'])
                    ->get();
                
                if ($pendingOrders->count() > 0) {
                    foreach ($pendingOrders as $order) {
                        $order->shift_id = $nextShift->id;
                        $order->save();
                    }
                }
            }

            // Force logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Flash error message
            session()->flash('error', "⏛ Shift Anda telah berakhir. Shift: {$shiftName} ({$startTimeFormatted} - {$endTimeFormatted} WIB).");

            return redirect('/login')->with('shift_blocked', true);
        }

        // Check if within 30 min before start or 30 min after end for warning
        // Hanya tampilkan warning di halaman dapur/admin, bukan di halaman pelanggan
        if ($request->routeIs('dapur') || $request->routeIs('reports') || $request->routeIs('pengaturan-audio') || $request->routeIs('tutup-hari') || $request->is('admin*')) {
            if ($now < $startTime && $now >= $toleranceStart) {
                $minutesUntil = $startTime->diffInMinutes($now);
                session()->flash('warning', "⏰ Shift Anda belum dimulai. Mulai dalam {$minutesUntil} menit.");
            } elseif ($now > $endTime && $now <= $toleranceEnd) {
                $minutesAfter = $now->diffInMinutes($endTime);
                session()->flash('warning', "⏰ Shift Anda sudah berakhir {$minutesAfter} menit lalu. Segera selesaikan pekerjaan.");
            }
        }

        return $next($request);
    }

    /**
     * Parse time string to Carbon instance
     */
    private function parseTime($time, $date)
    {
        if ($time instanceof \Carbon\Carbon) {
            return $date->copy()->setHour($time->hour)->setMinute($time->minute)->setSecond($time->second);
        }

        if (is_string($time)) {
            $parts = explode(':', $time);
            $hour = (int) $parts[0] ?? 0;
            $minute = (int) $parts[1] ?? 0;
            $second = (int) $parts[2] ?? 0;

            return $date->copy()->setHour($hour)->setMinute($minute)->setSecond($second);
        }

        return $date->copy();
    }
}
