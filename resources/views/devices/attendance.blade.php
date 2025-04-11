@extends('layouts.app') {{-- Asumsikan Anda memiliki layout utama --}}

@section('content')
    <div class="container">
        <h2 class="mb-4">Attendance</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered data-table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>SN</th>
                        <th>Employee ID</th>
                        <th>Timestamp</th>
                        <th>Method</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->id }}</td>
                            <td>{{ str_repeat('*', strlen($attendance->sn) - 4) . substr($attendance->sn, -4) }}</td>
                            <td>{{ $attendance->employee_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->timestamp)->format('d-m-Y h:i A') }}</td>

                            <td>
                                @if ($attendance->status2 == 15)
                                    face
                                @elseif ($attendance->status2 == 3)
                                    password
                                @elseif ($attendance->status2 == 1)
                                    fingerprint
                                @else
                                    {{ $attendance->status2 }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- source: https://stackoverflow.com/a/70119390 -->
        <div class="d-felx justify-content-center">
            {{ $attendances->links() }} {{-- Tampilkan pagination jika ada --}}
        </div>


    </div>
@endsection
