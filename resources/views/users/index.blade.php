@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin</h2>
    <ul>
        @foreach ($admins as $admin)
            <li>{{ $admin->name }} - {{ $admin->email }}</li>
        @endforeach
    </ul>

    <h2>Pihak Menyetujui (Approver)</h2>
    <ul>
        @foreach ($approvers as $approver)
            <li>{{ $approver->name }} - {{ $approver->email }}</li>
        @endforeach
    </ul>
</div>
@endsection
