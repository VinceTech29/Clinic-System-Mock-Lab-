<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Appointment;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Display all transactions
    public function index()
    {
        $transactions = Transaction::with(['appointment', 'patient'])->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    // Create payment form
    public function create()
    {
        $appointments = Appointment::where('status', 'completed')
            ->whereDoesntHave('transaction')
            ->with(['patient', 'doctor'])
            ->get();
        return view('transactions.create', compact('appointments'));
    }

    // Record payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online',
            'description' => 'nullable|string',
        ]);

        $appointment = Appointment::find($validated['appointment_id']);
        $consultationFee = $appointment->consultation_fee ?? 0;
        $remainingBalance = max(0, $consultationFee - $validated['amount']);

        $validated['patient_id'] = $appointment->patient_id;
        $validated['payment_status'] = $remainingBalance > 0 ? 'partial' : 'completed';
        $validated['remaining_balance'] = $remainingBalance;
        $validated['receipt_number'] = Transaction::generateReceiptNumber();

        Transaction::create($validated);
        return redirect()->route('transactions.index')->with('success', 'Payment recorded successfully!');
    }

    // Show transaction details
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // Process refund
    public function refund(Transaction $transaction)
    {
        if ($transaction->payment_status === 'refunded') {
            return back()->with('error', 'This payment has already been refunded.');
        }

        $transaction->update([
            'payment_status' => 'refunded',
            'remaining_balance' => $transaction->amount
        ]);

        return redirect()->route('transactions.index')->with('success', 'Refund processed successfully!');
    }

    // Revenue report
    public function report()
    {
        $totalRevenue = Transaction::where('payment_status', 'completed')->sum('amount');
        $pendingPayments = Transaction::where('payment_status', 'pending')->sum('amount');
        $totalTransactions = Transaction::count();

        // Revenue by doctor
        $revenueByDoctor = Transaction::join('appointments', 'transactions.appointment_id', '=', 'appointments.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->where('transactions.payment_status', 'completed')
            ->selectRaw('doctors.first_name, doctors.last_name, SUM(transactions.amount) as total')
            ->groupBy('doctors.id', 'doctors.first_name', 'doctors.last_name')
            ->get();

        return view('transactions.report', compact('totalRevenue', 'pendingPayments', 'totalTransactions', 'revenueByDoctor'));
    }
}
