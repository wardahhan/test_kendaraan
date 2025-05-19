@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Monitoring Kendaraan</h1>

    {{-- ADMIN --}}
    @if(auth()->user()->role === 'admin')
        <div class="row">
            <div class="col-md-4">
                <canvas id="fuelChart"></canvas>
            </div>
            <div class="col-md-4">
                <canvas id="serviceChart"></canvas>
            </div>
            <div class="col-md-4">
                <canvas id="usageChart"></canvas>
            </div>
        </div>

    {{-- APPROVER --}}
    @elseif(in_array(auth()->user()->role, ['approver1', 'approver2']))
        <div class="alert alert-info mt-4">
            <h4>Selamat datang, {{ auth()->user()->name }}!</h4>
            <p>Berikut adalah daftar permintaan kendaraan yang menunggu persetujuan Anda.</p>
        </div>

        @if(isset($approvalOrders) && count($approvalOrders) > 0)
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pemohon</th>
                        <th>Keperluan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvalOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->requester->name ?? '-' }}</td>
                            <td>{{ $order->purpose ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') ?? '-' }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mt-3">Tidak ada permintaan yang menunggu persetujuan saat ini.</p>
        @endif
    @endif
</div>

{{-- CHART SECTION UNTUK ADMIN --}}
@if(auth()->user()->role === 'admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const fuelData = @json($fuelData);
    const serviceData = @json($serviceData);
    const usageData = @json($usageData);

    function getPointColors(data) {
        return data.map((val, i) => i === 0 ? 'blue' : (val >= data[i - 1] ? 'green' : 'red'));
    }

    function setTotalText(id, label, total) {
        const container = document.getElementById(id);
        let totalEl = document.getElementById(id + '-total');
        if (!totalEl) {
            totalEl = document.createElement('p');
            totalEl.id = id + '-total';
            totalEl.style.fontWeight = 'bold';
            totalEl.style.marginBottom = '0.5rem';
            container.parentNode.insertBefore(totalEl, container);
        }
        totalEl.textContent = label + ': ' + total.toLocaleString();
    }

    setTotalText('fuelChart', 'Total BBM (Liter)', fuelData.reduce((a, b) => a + b, 0));
    setTotalText('serviceChart', 'Total Service', serviceData.reduce((a, b) => a + b, 0));
    setTotalText('usageChart', 'Total Jarak Tempuh (KM)', usageData.reduce((a, b) => a + b, 0));

    const chartConfigs = [
        {
            el: 'fuelChart',
            label: 'Total BBM (Liter)',
            data: fuelData,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            yLabel: 'Liter'
        },
        {
            el: 'serviceChart',
            label: 'Jumlah Service',
            data: serviceData,
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            yLabel: 'Jumlah'
        },
        {
            el: 'usageChart',
            label: 'Jarak Tempuh (KM)',
            data: usageData,
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            yLabel: 'KM'
        }
    ];

    chartConfigs.forEach(cfg => {
        new Chart(document.getElementById(cfg.el), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: cfg.label,
                    data: cfg.data,
                    borderColor: cfg.borderColor,
                    backgroundColor: cfg.backgroundColor,
                    fill: true,
                    pointBackgroundColor: getPointColors(cfg.data),
                    stepped: true,
                    tension: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: { mode: 'index', intersect: false },
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: cfg.yLabel }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
