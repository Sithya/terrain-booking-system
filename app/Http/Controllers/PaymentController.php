<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index()
    {
        $payments = Payment::whereHas('booking', function ($query) {
                $query->where('renter_id', auth()->id());
            })
            ->with(['booking.terrain'])
            ->latest('payment_date')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Booking $booking)
    {
        $this->authorize('create', [Payment::class, $booking]);
        return view('payments.create', compact('booking'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['payment_date'] = now();
        $data['status'] = 'paid'; // Set default status

        $payment = Payment::create($data);

        // Update booking status to approved after payment
        $payment->booking->update(['status' => 'approved']);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment processed successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.terrain']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment record deleted successfully.');
    }
}
