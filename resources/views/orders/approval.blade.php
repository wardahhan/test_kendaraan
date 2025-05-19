@extends('layouts.app')

@section('content')
    <h1>Approval Orders</h1>

    @foreach ($orders as $order)
        <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 6px;">
            {{-- Debug info untuk cek user dan status --}}
            <pre>
User Login ID: {{ auth()->user()->id }}
Approver Level 1 ID: {{ $order->approver_level_1 }}
Status Level 1: {{ $order->approver_level_1_status }}
Approver Level 2 ID: {{ $order->approver_level_2 }}
Status Level 2: {{ $order->approver_level_2_status }}
            </pre>

            <strong>Kendaraan:</strong> {{ $order->vehicle->name }} <br>
            <strong>Keperluan:</strong> {{ $order->purpose }} <br>
            <strong>Status:</strong> {{ ucfirst($order->status) }} <br>
            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }} 
            sampai {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}

            @if(auth()->user()->id == $order->approver_level_1 && $order->approver_level_1_status === 'pending')
                <form action="{{ route('orders.approve', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="level" value="1">
                    <button type="submit" class="btn btn-success">Setujui</button>
                </form>
                <form action="{{ route('orders.reject', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="level" value="1">
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>
            @elseif(auth()->user()->id == $order->approver_level_2 && $order->approver_level_1_status === 'approved' && $order->approver_level_2_status === 'pending')
                <form action="{{ route('orders.approve', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="level" value="2">
                    <button type="submit" class="btn btn-success">Setujui</button>
                </form>
                <form action="{{ route('orders.reject', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="level" value="2">
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>
            @else
                <p><em>Sudah diproses.</em></p>
            @endif
        </div>
    @endforeach
@endsection
