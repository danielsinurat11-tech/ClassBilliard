<?php

namespace App\Http\Controllers;

use App\Models\meja_billiard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class TableController extends Controller
{
    /**
     * Menampilkan daftar semua meja
     */
    public function index()
    {
        $tables = meja_billiard::orderBy('name', 'asc')->get();
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Menampilkan form tambah meja baru
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Menyimpan meja baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:meja_billiards,name',
            'room' => 'required|string|max:255',
        ]);

        $slug = Str::slug($validated['name']);
        
        // Extract nomor meja dari nama (misalnya "Meja 01" -> "01")
        $tableNumber = preg_replace('/[^0-9]/', '', $validated['name']);
        if (empty($tableNumber)) {
            $tableNumber = $validated['name']; // Fallback ke nama jika tidak ada angka
        }
        
        // Generate URL untuk QR Code dengan parameter table (nomor saja) dan room
        $url = route('menu') . '?table=' . urlencode($tableNumber) . '&room=' . urlencode($validated['room']);
        
        // Generate QR Code menggunakan package endroid/qr-code
        $result = (new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 500,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        ))->build();
        
        // Simpan QR Code ke storage
        $qrCodePath = 'qrcodes/' . $slug . '.png';
        Storage::disk('public')->put($qrCodePath, $result->getString());
        
        $table = meja_billiard::create([
            'name' => $validated['name'],
            'room' => $validated['room'],
            'slug' => $slug,
            'qrcode' => $qrCodePath,
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan dan QR Code telah di-generate!');
    }

    /**
     * Generate QR Code untuk meja yang sudah ada
     */
    public function generateQR($id)
    {
        $table = meja_billiard::findOrFail($id);
        
        // Extract nomor meja dari nama (misalnya "Meja 01" -> "01")
        $tableNumber = preg_replace('/[^0-9]/', '', $table->name);
        if (empty($tableNumber)) {
            $tableNumber = $table->name; // Fallback ke nama jika tidak ada angka
        }
        
        // Generate URL untuk QR Code dengan parameter table (nomor saja) dan room
        $url = route('menu') . '?table=' . urlencode($tableNumber) . '&room=' . urlencode($table->room ?? '');
        
        // Generate QR Code menggunakan package endroid/qr-code
        $result = (new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 500,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        ))->build();
        
        // Simpan QR Code ke storage
        $qrCodePath = 'qrcodes/' . $table->slug . '.png';
        Storage::disk('public')->put($qrCodePath, $result->getString());
        
        // Update table dengan path QR code
        $table->qrcode = $qrCodePath;
        $table->save();
        
        return redirect()->route('admin.tables.index')
            ->with('success', 'QR Code berhasil di-generate untuk ' . $table->name);
    }

    /**
     * Menampilkan halaman print QR Code untuk meja tertentu
     */
    public function showBarcode($id)
    {
        $table = meja_billiard::findOrFail($id);
        
        // Extract nomor meja dari nama (misalnya "Meja 01" -> "01")
        $tableNumber = preg_replace('/[^0-9]/', '', $table->name);
        if (empty($tableNumber)) {
            $tableNumber = $table->name; // Fallback ke nama jika tidak ada angka
        }
        
        $url = route('menu') . '?table=' . urlencode($tableNumber) . '&room=' . urlencode($table->room ?? '');
        
        return view('admin.tables.print-barcode', compact('table', 'url'));
    }

    /**
     * Hapus meja
     */
    public function destroy($id)
    {
        $table = meja_billiard::findOrFail($id);
        
        // Hapus file QR Code jika ada
        if ($table->qrcode && Storage::disk('public')->exists($table->qrcode)) {
            Storage::disk('public')->delete($table->qrcode);
        }
        
        $table->delete();
        
        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus!');
    }
}

