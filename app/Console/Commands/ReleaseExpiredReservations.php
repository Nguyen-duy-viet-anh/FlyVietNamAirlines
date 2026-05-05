<?php

namespace App\Console\Commands;

use App\Models\AppBooking;
use App\Models\Flight;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredReservations extends Command
{
    /**
     * Tên và chữ ký của command.
     */
    protected $signature = 'bookings:release-expired
                            {--minutes=30 : Số phút tối đa cho booking pending}';

    /**
     * Mô tả command.
     */
    protected $description = 'Trả lại ghế cho các booking pending quá hạn (mặc định 30 phút)';

    public function handle(): int
    {
        $minutes = (int) $this->option('minutes');

        $expiredBookings = AppBooking::where('status', 'pending')
            ->where('seats_reserved', true)
            ->where('created_at', '<', now()->subMinutes($minutes))
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Không có booking pending nào quá hạn.');
            return self::SUCCESS;
        }

        $released = 0;
        $failed = 0;

        foreach ($expiredBookings as $booking) {
            try {
                DB::transaction(function () use ($booking) {
                    // Lock booking để tránh race condition với VNPay callback
                    $locked = AppBooking::whereKey($booking->id)
                        ->where('status', 'pending')
                        ->where('seats_reserved', true)
                        ->lockForUpdate()
                        ->first();

                    if (!$locked) {
                        return; // Đã được xử lý bởi process khác
                    }

                    $seatCount = max(0, (int) $locked->adult_count + (int) $locked->child_count);
                    $ticketClass = $locked->ticket_class ?? 'economy';

                    if ($seatCount > 0) {
                        Flight::releaseSeats((int) $locked->outbound_flight_id, $seatCount, $ticketClass);

                        if ($locked->return_flight_id) {
                            Flight::releaseSeats((int) $locked->return_flight_id, $seatCount, $ticketClass);
                        }
                    }

                    $locked->update([
                        'status' => 'cancelled',
                        'seats_reserved' => false,
                    ]);
                });

                $released++;
                $this->line("  ✓ Đã trả ghế booking #{$booking->id} ({$booking->booking_code})");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Lỗi booking #{$booking->id}: {$e->getMessage()}");
                Log::error("ReleaseExpiredReservations failed for booking #{$booking->id}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Hoàn tất: {$released} đã trả ghế, {$failed} lỗi.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
