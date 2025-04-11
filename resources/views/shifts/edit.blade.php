@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Shift</h2>
        <form method="post" action="{{ route('shifts.update', $shift->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group mt-2">
                <label for="name">Shift Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                    placeholder="Enter shift name" value="{{ old('name', $shift->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <h5 class="mt-2">Active Days</h5>

            <div class="form-group">
                @php
                    $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                @endphp
                @foreach ($days as $day)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input day-checkbox @error($day) is-invalid @enderror" type="checkbox"
                            name="{{ $day }}" id="{{ $day }}" value="1"
                            {{ old($day, $shift->$day) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                    </div>
                @endforeach
                <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-days">Mark All Days</button>
                @foreach ($days as $day)
                    @error($day)
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                @endforeach
            </div>

            <div class="form-group mt-2">
                <label for="entry_time">Entry Time</label>
                <input type="time" name="entry_time" class="form-control @error('entry_time') is-invalid @enderror"
                    id="entry_time"
                    value="{{ old('entry_time', \Carbon\Carbon::parse($shift->entry_time)->format('H:i')) }}">
                @error('entry_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="late_entry">Late Entry (minutes)</label>
                <input type="number" name="late_entry" class="form-control @error('late_entry') is-invalid @enderror"
                    id="late_entry" placeholder="Enter late entry minutes"
                    value="{{ old('late_entry', $shift->late_entry) }}">
                @error('late_entry')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="out_time">Out Time</label>
                <input type="time" name="out_time" class="form-control @error('out_time') is-invalid @enderror"
                    id="out_time" value="{{ old('out_time', \Carbon\Carbon::parse($shift->out_time)->format('H:i')) }}">
                @error('out_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="early_out_time">Early Out (minutes)</label>
                <input type="number" name="early_out_time"
                    class="form-control @error('early_out_time') is-invalid @enderror" id="early_out_time"
                    placeholder="Enter early out minutes" value="{{ old('early_out_time', $shift->early_out_time) }}">
                @error('early_out_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Shift</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('toggle-days').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.day-checkbox');
            let allChecked = [...checkboxes].every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
            this.textContent = allChecked ? 'Mark All Days' : 'Unmark All Days';
        });
    </script>
@endsection
