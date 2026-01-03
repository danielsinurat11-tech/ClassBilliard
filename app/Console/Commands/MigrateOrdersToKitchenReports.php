<?php

namespace App\Console\Commands;

use App\Models\KitchenReport;
use App\Models\orders;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MigrateOrdersToKitchenReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kitchen:migrate-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan data order yang sudah completed ke kitchen_reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai migrasi data order ke kitchen_reports...');

        // Ambil semua order yang sudah completed
        $orders = orders::with('orderItems')
            ->where('status', 'completed')
            ->get();

        $this->info("Ditemukan {$orders->count()} order yang sudah completed.");

        $migrated = 0;
        $skipped = 0;

        foreach ($orders as $order) {
            // Cek apakah sudah ada di kitchen_reports
            $existingReport = KitchenReport::where('order_id', $order->id)->first();

            if ($existingReport) {
                $skipped++;

                continue;
            }

            try {
                // Simpan order items sebagai JSON
                $orderItems = $order->orderItems->map(function ($item) {
                    return [
                        'menu_name' => $item->menu_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'image' => $item->image,
                    ];
                })->toArray();

                KitchenReport::create([
                    'order_id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table_number,
                    'room' => $order->room,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'order_items' => $orderItems,
                    'order_date' => Carbon::parse($order->created_at)->format('Y-m-d'),
                    'completed_at' => Carbon::parse($order->updated_at),
                ]);

                $migrated++;
            } catch (\Exception $e) {
                $this->error("Error migrasi order ID {$order->id}: ".$e->getMessage());
            }
        }

        $this->info('Migrasi selesai!');
        $this->info("  - Berhasil dimigrasikan: {$migrated}");
        $this->info("  - Dilewati (sudah ada): {$skipped}");

        return Command::SUCCESS;
    }
}
