<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundCompletedMail;

class RefundController extends Controller
{
    // 1. Danh sach yeu cau hoan ve
    public function index(Request $request)
    {
        $query = Refund::with(['booking.outboundFlight.origin', 'booking.outboundFlight.destination']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhereHas('booking', function ($b) use ($search) {
                      $b->where('booking_code', 'like', "%{$search}%")
                        ->orWhere('passenger_name', 'like', "%{$search}%")
                        ->orWhere('passenger_email', 'like', "%{$search}%")
                        ->orWhere('passenger_phone', 'like', "%{$search}%");
                  });
            });
        }

        $refunds = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());

        return view('admin.refunds.index', compact('refunds'));
    }

    // 2. Xem chi tiet yeu cau hoan ve
    public function show($id)
    {
        $refund = Refund::with([
            'booking.outboundFlight.airline', 'booking.outboundFlight.origin', 'booking.outboundFlight.destination',
            'booking.returnFlight.airline', 'booking.returnFlight.origin', 'booking.returnFlight.destination',
            'booking.transaction',
            'requester',
            'processor'
        ])->findOrFail($id);

        return view('admin.refunds.show', compact('refund'));
    }

    // 3. Cap nhat trang thai hoan ve
    public function updateStatus(Request $request, $id)
    {
        $refund = Refund::with(['booking', 'requester'])->findOrFail($id);
        $wasCompleted = $refund->status === 'completed';

        $data = $request->validate([
            'status' => ['required', 'in:requested,processing,completed,failed,cancelled'],
        ]);

        $refund->status = $data['status'];
        $originalAmount = null;
        $refundRate = 0.8;
        if (in_array($data['status'], ['processing', 'completed'], true)) {
            $refund->processed_by = Auth::id();
        }
        if ($data['status'] === 'completed') {
            $refund->processed_at = now();

            if ($refund->booking) {
                $originalAmount = (float) $refund->booking->total_amount;
            }
            if ($originalAmount === null || $originalAmount <= 0) {
                $originalAmount = (float) $refund->amount;
            }
            $refundAmount = round($originalAmount * $refundRate, 2);

            $refund->amount = $refundAmount;
            $meta = is_array($refund->meta) ? $refund->meta : [];
            $refund->meta = array_merge($meta, [
                'original_amount' => $originalAmount,
                'refund_rate' => $refundRate,
            ]);

            if ($refund->booking) {
                $refund->booking->payment_status = 'refunded';
                if ($refund->booking->status !== 'cancelled') {
                    $refund->booking->status = 'cancelled';
                }
                $refund->booking->save();
            }
        }

        $refund->save();

        if ($data['status'] === 'completed' && !$wasCompleted) {
            $recipientEmail = $refund->booking->passenger_email ?? $refund->requester->email ?? null;
            if ($recipientEmail) {
                try {
                    $originalAmount = $originalAmount ?? (float) ($refund->meta['original_amount'] ?? 0);
                    Mail::to($recipientEmail)->send(new RefundCompletedMail($refund, $originalAmount, $refundRate));

                    $meta = is_array($refund->meta) ? $refund->meta : [];
                    $refund->meta = array_merge($meta, [
                        'completed_email_sent' => true,
                        'completed_email_sent_at' => now()->toDateTimeString(),
                    ]);
                    $refund->save();
                } catch (\Exception $e) {
                    return back()->with('success', 'Da cap nhat trang thai hoan ve. Khong gui duoc email thong bao.');
                }
            }
        }

        return back()->with('success', 'Đã cập nhật trạng thái hoàn vé.');
    }
}
