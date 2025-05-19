@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Pemesanan Kendaraan</h1>

    {{-- Tombol export langsung tanpa filter --}}
    <a href="{{ route('orders.export') }}" class="btn btn-success mb-3">Export Orders to Excel</a>

    {{-- Form export dengan filter bulan --}}
    <form method="GET" action="{{ route('reports.export') }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="month" name="month" class="form-control" required>
            </div> 
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Export Excel</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kendaraan</th>
                <th>Driver</th>
                <th>Keperluan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->start_date }}</td>
                <td>{{ $order->vehicle->name }}</td>
                <td>{{ $order->driver->name ?? '-' }}</td>
                <td>{{ $order->purpose }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
