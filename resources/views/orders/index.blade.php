@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pemesanan Kendaraan</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Driver</th>
                <th>Kendaraan</th>
                <th>Approver Level 1</th>
                <th>Approver Level 2</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->driver->name ?? '-' }}</td>
                    <td>{{ $order->vehicle->name ?? '-' }}</td>
                    <td>{{ $order->approverLevel1->name ?? '-' }}</td>
                    <td>{{ $order->approverLevel2->name ?? '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}
                    </td>
                    <td>
                        @php
                            $statusClass = [
                                'pending' => 'secondary',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            ][$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada pemesanan kendaraan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
