@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Input Pemesanan Kendaraan</h1>

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        {{-- Kendaraan --}}
        <div class="form-group mb-3">
            <label for="vehicle_id">Kendaraan</label>
            <select name="vehicle_id" id="vehicle_id" class="form-control @error('vehicle_id') is-invalid @enderror" required>
                <option value="">-- Pilih Kendaraan --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                        {{ $vehicle->name }}
                    </option>
                @endforeach
            </select>
            @error('vehicle_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Driver --}}
        <div class="form-group mb-3">
            <label for="driver_id">Driver</label>
            <select name="driver_id" id="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                <option value="">-- Pilih Driver --</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
            @error('driver_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Approver Level 1 --}}
        <div class="form-group mb-3">
            <label for="approver_level_1">Approver Level 1</label>
            <select name="approver_level_1" id="approver_level_1" class="form-control @error('approver_level_1') is-invalid @enderror" required>
                <option value="">-- Pilih Approver Level 1 --</option>
                @foreach($approversLevel1 as $approver)
                    <option value="{{ $approver->id }}" {{ old('approver_level_1') == $approver->id ? 'selected' : '' }}>
                        {{ $approver->name }} @if($approver->role == 'admin') (Admin) @endif
                    </option>
                @endforeach
            </select>
            @error('approver_level_1')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Approver Level 2 --}}
        <div class="form-group mb-3">
            <label for="approver_level_2">Approver Level 2</label>
            <select name="approver_level_2" id="approver_level_2" class="form-control @error('approver_level_2') is-invalid @enderror" required>
                <option value="">-- Pilih Approver Level 2 --</option>
                @foreach($approversLevel2 as $approver)
                    <option value="{{ $approver->id }}" {{ old('approver_level_2') == $approver->id ? 'selected' : '' }}>
                        {{ $approver->name }} @if($approver->role == 'admin') (Admin) @endif
                    </option>
                @endforeach
            </select>
            @error('approver_level_2')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Mulai --}}
        <div class="form-group mb-3">
            <label for="start_date">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" required value="{{ old('start_date') }}">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Selesai --}}
        <div class="form-group mb-3">
            <label for="end_date">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" required value="{{ old('end_date') }}">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Keperluan --}}
        <div class="form-group mb-3">
            <label for="purpose">Keperluan</label>
            <textarea name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" rows="3" required placeholder="Jelaskan keperluan">{{ old('purpose') }}</textarea>
            @error('purpose')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
