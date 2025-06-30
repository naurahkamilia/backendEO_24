<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_event' => 'required|exists:events,id_event',
        ]);

        $payment = new Payment();
        $payment->id_users = Auth::id();
        $payment->id_event = $request->id_event;
        $payment->status = 'pending';
        $payment->save();

        return response()->json(['message' => 'Payment created successfully.', 'data' => $payment]);
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $payment = Payment::findOrFail($id);
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran');

        $payment->bukti_pembayaran = $path;
        $payment->tanggal_bayar = Carbon::now();
        $payment->status = 'pending';
        $payment->save();

        return response()->json(['message' => 'Bukti pembayaran berhasil diupload.', 'data' => $payment]);
    }

    public function verify($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'verified';
        $payment->save();

        return response()->json(['message' => 'Payment verified.', 'data' => $payment]);
    }
}
