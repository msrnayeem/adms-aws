<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class LogsController extends Controller
{
    public function viewLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logLines = [];
        $message = 'No logs available or the log file does not exist.';

        if (File::exists($logFile)) {
            $logContents = File::get($logFile);

            // Extract log entries with regex pattern matching
            preg_match_all(
                '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] ([a-z]+)\.(\w+): (.*)$/im',
                $logContents,
                $matches,
                PREG_SET_ORDER
            );

            if (!empty($matches)) {
                $message = 'Showing the most recent logs:';

                // Process matches into the desired format (most recent first)
                foreach (array_reverse($matches) as $match) {
                    $timestamp = $match[1];
                    $logLevel = strtoupper($match[3]);
                    $logMessage = $match[4];

                    // Format time using Carbon
                    $formattedTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)
                        ->format('Y-m-d h:i:s A');

                    $logLines[] = [
                        'time' => $formattedTime,
                        'level' => $logLevel,
                        'message' => $logMessage
                    ];
                }
            }
        }

        return view('logs.view', compact('logLines', 'message'));
    }

    public function clearLogs()
    {
        // Path to the Laravel log file
        $logFile = storage_path('logs/laravel.log');

        if (File::exists($logFile)) {
            // Clear the content of the log file
            File::put($logFile, '');

            // Optionally log the action of clearing logs
            \Log::info('Log file has been cleared.');

            return redirect()->back()->with('status', 'Log file has been cleared successfully!');
        }

        return redirect()->back()->with('error', 'Log file not found.');
    }
}
