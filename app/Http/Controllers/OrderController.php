<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Log;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderController extends Controller
{
    /**
     * Tampilkan semua data pemesanan dengan relasi yang diperlukan.
     */
    public function index()
    {
        $orders = Order::with(['vehicle', 'requester', 'driver', 'approverLevel1', 'approverLevel2'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Export data pemesanan ke Excel.
     */
    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    /**
     * Form input pemesanan, hanya untuk admin.
     */
    public function create()
    {
        $this->authorizeAdmin();

        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $approversLevel1 = User::whereIn('role', ['approver1', 'admin'])->get();
        $approversLevel2 = User::whereIn('role', ['approver2', 'admin'])->get();

        return view('orders.create', compact('vehicles', 'drivers', 'approversLevel1', 'approversLevel2'));
    }

    /**
     * Simpan data pemesanan baru.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:users,id',
            'approver_level_1' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || !in_array($user->role, ['approver1', 'admin'])) {
                        $fail('Approver Level 1 yang dipilih tidak valid.');
                    }
                },
            ],
            'approver_level_2' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || !in_array($user->role, ['approver2', 'admin'])) {
                        $fail('Approver Level 2 yang dipilih tidak valid.');
                    }
                },
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'purpose' => 'required|string|max:255',
        ]);

        $order = Order::create([
            'vehicle_id' => $validated['vehicle_id'],
            'requester_id' => auth()->id(),
            'driver_id' => $validated['driver_id'],
            'approver_level_1' => $validated['approver_level_1'],
            'approver_level_2' => $validated['approver_level_2'],
            'status' => 'pending',
            'approver_level_1_status' => 'pending',
            'approver_level_2_status' => 'pending',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'purpose' => $validated['purpose'],
        ]);

        // Log aktivitas input pemesanan
        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Input Pemesanan',
            'model' => 'Order',
            'model_id' => $order->id,
        ]);

        return redirect()->route('orders.index')->with('success', 'Pemesanan berhasil dibuat.');
    }

    /**
     * Tampilkan detail pemesanan berdasarkan ID.
     */
    public function show($id)
    {
        $order = Order::with(['vehicle', 'requester', 'driver', 'approverLevel1', 'approverLevel2'])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Daftar pemesanan yang menunggu approval.
     */
    public function approval()
    {
        $orders = Order::with(['vehicle', 'requester', 'driver'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->get();

        return view('orders.approval', compact('orders'));
    }

    /**
     * Proses approval pemesanan oleh level 1 atau level 2.
     */
    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $level = $request->input('level');

        if ($level == 1) {
            if (auth()->id() === $order->approver_level_1 && $order->approver_level_1_status === 'pending') {
                $order->approver_level_1_status = 'approved';
                $order->approved_at_level_1 = now();
                $order->approved_by_level_1 = auth()->id();
                $order->save();

                Log::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Approve Level 1 Pemesanan',
                    'model' => 'Order',
                    'model_id' => $order->id,
                ]);

                return redirect()->back()->with('success', 'Pemesanan disetujui Level 1.');
            }
        } elseif ($level == 2) {
            if (
                auth()->id() === $order->approver_level_2 &&
                $order->approver_level_1_status === 'approved' &&
                $order->approver_level_2_status === 'pending'
            ) {
                $order->approver_level_2_status = 'approved';
                $order->approved_at_level_2 = now();
                $order->approved_by_level_2 = auth()->id();
                $order->status = 'approved'; // Final approval
                $order->save();

                Log::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Approve Level 2 Pemesanan',
                    'model' => 'Order',
                    'model_id' => $order->id,
                ]);

                return redirect()->back()->with('success', 'Pemesanan disetujui Level 2.');
            }
        }

        return redirect()->back()->with('error', 'Tidak dapat melakukan approval.');
    }

    /**
     * Proses penolakan pemesanan oleh level 1 atau level 2.
     */
    public function reject(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $level = $request->input('level');

        if ($level == 1) {
            if (auth()->id() === $order->approver_level_1 && $order->approver_level_1_status === 'pending') {
                $order->approver_level_1_status = 'rejected';
                $order->rejected_at = now();
                $order->rejected_by = auth()->id();
                $order->status = 'rejected';
                $order->save();

                Log::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Reject Level 1 Pemesanan',
                    'model' => 'Order',
                    'model_id' => $order->id,
                ]);

                return redirect()->back()->with('success', 'Pemesanan ditolak Level 1.');
            }
        } elseif ($level == 2) {
            if (
                auth()->id() === $order->approver_level_2 &&
                $order->approver_level_1_status === 'approved' &&
                $order->approver_level_2_status === 'pending'
            ) {
                $order->approver_level_2_status = 'rejected';
                $order->rejected_at = now();
                $order->rejected_by = auth()->id();
                $order->status = 'rejected';
                $order->save();

                Log::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Reject Level 2 Pemesanan',
                    'model' => 'Order',
                    'model_id' => $order->id,
                ]);

                return redirect()->back()->with('success', 'Pemesanan ditolak Level 2.');
            }
        }

        return redirect()->back()->with('error', 'Tidak dapat melakukan penolakan.');
    }

    /**
     * Batasi akses create/store hanya untuk admin.
     */
    protected function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }
    }
}
