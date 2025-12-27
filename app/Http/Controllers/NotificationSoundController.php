<?php

namespace App\Http\Controllers;

use App\Models\NotificationSound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NotificationSoundController extends Controller
{
    /**
     * Get all notification sounds
     */
    public function index()
    {
        $sounds = NotificationSound::orderBy('name', 'asc')
            ->get();
        
        return response()->json($sounds);
    }

    /**
     * Get active notification sound (for kitchen)
     * Gets the sound that admin has selected in their settings
     */
    public function getActive()
    {
        // Get active sound from cache (set by admin)
        $activeSoundId = cache()->get('active_notification_sound_id');
        
        if ($activeSoundId) {
            $sound = NotificationSound::find($activeSoundId);
            if ($sound) {
                $filePath = $sound->file_path;
                if (str_starts_with($filePath, 'sounds/')) {
                    $url = asset('storage/' . $filePath);
                } else {
                    $url = asset('assets/sounds/' . $sound->filename);
                }
                
                return response()->json([
                    'success' => true,
                    'sound' => [
                        'id' => $sound->id,
                        'name' => $sound->name,
                        'filename' => $sound->filename,
                        'url' => $url
                    ]
                ]);
            }
        }
        
        // Fallback: get the most recently created sound
        $sound = NotificationSound::orderBy('created_at', 'desc')->first();
        
        if ($sound) {
            $filePath = $sound->file_path;
            if (str_starts_with($filePath, 'sounds/')) {
                $url = asset('storage/' . $filePath);
            } else {
                $url = asset('assets/sounds/' . $sound->filename);
            }
            
            return response()->json([
                'success' => true,
                'sound' => [
                    'id' => $sound->id,
                    'name' => $sound->name,
                    'filename' => $sound->filename,
                    'url' => $url
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'sound' => null
        ]);
    }

    /**
     * Set active notification sound
     */
    public function setActive(Request $request, $id)
    {
        try {
            $sound = NotificationSound::findOrFail($id);
            
            // Store active sound ID in session or cache
            // For now, we'll use cache with a key
            cache()->put('active_notification_sound_id', $id, now()->addDays(30));
            
            return response()->json([
                'success' => true,
                'message' => 'Audio aktif berhasil diubah',
                'sound' => $sound
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah audio aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload new notification sound
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:2048' // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('audio');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('sounds', $filename, 'public');

            $sound = NotificationSound::create([
                'name' => $request->name,
                'filename' => $filename,
                'file_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Audio berhasil diupload',
                'sound' => $sound
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload audio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete notification sound
     */
    public function destroy($id)
    {
        try {
            $sound = NotificationSound::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($sound->file_path)) {
                Storage::disk('public')->delete($sound->file_path);
            }

            $sound->delete();

            return response()->json([
                'success' => true,
                'message' => 'Audio berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus audio: ' . $e->getMessage()
            ], 500);
        }
    }

}
