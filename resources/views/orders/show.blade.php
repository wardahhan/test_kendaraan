@if(auth()->id() === $order->approver_level_1 && $order->status === 'pending')
    <form method="POST" action="{{ route('orders.approveLevel1', $order->id) }}">
        @csrf
        <button type="submit" class="btn btn-success">Setujui Level 1</button>
    </form>
@endif

@if(auth()->id() === $order->approver_level_2 && $order->status === 'approved_level_1')
    <form method="POST" action="{{ route('orders.approveLevel2', $order->id) }}">
        @csrf
        <button type="submit" class="btn btn-success">Setujui Level 2</button>
    </form>
@endif

@if(in_array(auth()->id(), [$order->approver_level_1, $order->approver_level_2]) && $order->status === 'pending')
    <form method="POST" action="{{ route('orders.reject', $order->id) }}">
        @csrf
        <button type="submit" class="btn btn-danger">Tolak</button>
    </form>
@endif