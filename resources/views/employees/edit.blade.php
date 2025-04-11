@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Employee</h2>
        <form method="post" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')

            <div class="form-group mt-2">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                    placeholder="Enter employee name" value="{{ old('name', $employee->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" placeholder="Enter employee email" value="{{ old('email', $employee->email) }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="zk_device_id">ZK Device ID</label>
                <input type="number" name="zk_device_id" class="form-control @error('zk_device_id') is-invalid @enderror"
                    id="zk_device_id" placeholder="Enter ZK Device ID"
                    value="{{ old('zk_device_id', $employee->zk_device_id) }}">
                @error('zk_device_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="shift_id">Shift</label>
                <select name="shift_id" class="form-control @error('shift_id') is-invalid @enderror" id="shift_id">
                    <option value="" {{ is_null($employee->shift_id) ? 'selected' : '' }}>No Shift</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}"
                            {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->entry_time }} - {{ $shift->out_time }})
                        </option>
                    @endforeach
                </select>
                @error('shift_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group mt-4">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Employee</button>
            </div>
        </form>
    </div>
@endsection
