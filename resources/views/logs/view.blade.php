@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Laravel Log File</h3>
                <div class="card-tools">
                    <a href="{{ route('logs.clear') }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to clear the log file?');">
                        <i class="fas fa-trash-alt"></i> Clear Logs
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @elseif (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <p>{{ $message }}</p>

                @if (!empty($logLines))
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="logsTable">
                            <thead>
                                <tr>
                                    <th width="17%">Time</th>
                                    <th>Level</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logLines as $log)
                                    <tr>
                                        <td>{{ $log['time'] }}</td>
                                        <td>{{ $log['level'] }}</td>
                                        <td>{{ $log['message'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
