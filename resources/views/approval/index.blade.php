@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Persetujuan Pemesanan Kendaraan</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Kendaraan</th>
                <th>Driver</th>
                <th>Tanggal</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->vehicle->name }}</td>
                <td>{{ $order->driver->name ?? '-' }}</td>
                <td>{{ $order->start_date }} - {{ $order->end_date }}</td>
                <td>{{ $order->purpose }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>
                    <form method="POST" action="{{ route('orders.approve', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('orders.reject', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
