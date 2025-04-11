@extends('layouts.app') {{-- Asumsikan Anda memiliki layout utama --}}

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Shifts</h2>
            <a href="{{ route('shifts.create') }}" class="btn btn-primary">Create Shift</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-success">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered data-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Entry Time</th>
                        <th>Late Entry (min)</th>
                        <th>Out Time</th>
                        <th>Early Out (min)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $shift)
                        <tr>
                            <td>{{ $shift->name }}</td>
                            <td>{{ $shift->saturday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->sunday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->monday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->tuesday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->wednesday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->thursday ? '✔' : '✖' }}</td>
                            <td>{{ $shift->friday ? '✔' : '✖' }}</td>
                            <td>{{ \Carbon\Carbon::parse($shift->entry_time)->format('h:i A') }}</td>
                            <td>{{ $shift->late_entry }} min</td>
                            <td>{{ \Carbon\Carbon::parse($shift->out_time)->format('h:i A') }}</td>
                            <td>{{ $shift->early_out_time }} min</td>
                            <td>
                                <a href="{{ route('shifts.edit', $shift) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>



    </div>
@endsection
