<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\iclockController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


Route::get('devices', [DeviceController::class, 'Index'])->name('devices.index');
Route::get('devices-log', [DeviceController::class, 'DeviceLog'])->name('devices.DeviceLog');
Route::get('finger-log', [DeviceController::class, 'FingerLog'])->name('devices.FingerLog');
Route::get('attendance', [DeviceController::class, 'Attendance'])->name('devices.Attendance');

// handshake
Route::get('/iclock/cdata', [iclockController::class, 'handshake']);
// request dari device
Route::post('/iclock/cdata', [iclockController::class, 'receiveRecords']);

Route::get('/iclock/test', [iclockController::class, 'test']);
Route::get('/iclock/getrequest', [iclockController::class, 'getrequest']);


Route::get('/', function () {
    return redirect('devices');
});
// shifts
Route::resource('shifts', ShiftController::class)->except(['show', 'destroy']);
//users
Route::resource('employees', EmployeeController::class)->except(['destroy']);



Route::get('time', function () {
    return [
        'current_time' => now(),
        'timezone' => config('app.timezone')
    ];
});
