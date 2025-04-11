@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Employees</h2>
            <a href="{{ route('employees.create') }}" class="btn btn-primary mb-2"><i class="fas fa-plus"></i> New
                Employee</a>
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

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>ZK Device ID</th>
                    <th>Shift</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->zk_device_id }}</td>
                        <td>{{ $employee->shift->name ?? 'Not Assigned' }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $employee) }}"><i class="fas fa-edit"></i>Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
