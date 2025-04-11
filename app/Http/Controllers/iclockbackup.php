<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;


class iclockController extends Controller
{

    public function __invoke(Request $request) {}

    // handshake
    public function handshake(Request $request)
    {

        $data = [
            'url' => json_encode($request->all()),
            'data' => $request->getContent(),
            'sn' => $request->input('SN'),
            'option' => $request->input('option'),
        ];
        DB::table('device_log')->insert($data);

        // update status device
        DB::table('devices')->updateOrInsert(
            ['no_sn' => $request->input('SN')],
            ['online' => Carbon::now('Asia/Dhaka')]
        );


        try {
        // Send data to another server
        $response = Http::post("https://hrt.bluedreamgroup.com/api/receive-handshake", [
            'SN'     => $request->input('SN'),
            'option' => Carbon::now('Asia/Dhaka'),
        ]);

        // You can handle the response if needed, e.g., log or process it
        if ($response->successful()) {
            // Handle successful response
        } else {
            // Handle unsuccessful response, if needed
        }
    } catch (\Exception $e) {
        // Log the error or handle it
        Log::info("Error sending request to external server: " . $e->getMessage());
    }

        $r = "GET OPTION FROM: {$request->input('SN')}\r\n" .
            "Stamp=9999\r\n" .
            "OpStamp=" . time() . "\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "ResLogDay=18250\r\n" .
            "ResLogDelCount=10000\r\n" .
            "ResLogCount=50000\r\n" .
            "TransTimes=00:00;14:05\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=1111000000\r\n" .
            //  "TimeZone=7\r\n" .
            "Realtime=1\r\n" .
            "Encrypt=0";

        return $r;
    }
    //$r = "GET OPTION FROM:%s{$request->SN}\nStamp=".strtotime('now')."\nOpStamp=1565089939\nErrorDelay=30\nDelay=10\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nTimeZone=7\nRealtime=1\nEncrypt=0\n";
    // implementasi https://docs.nufaza.com/docs/devices/zkteco_attendance/push_protocol/
    // setting timezone
    // request absensi

    public function receiveRecords(Request $request)
    {
        // if ($request->input('table') === 'ATTLOG') {
        //     // Extract employee ID from request content and log it
        //     $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());

        //     foreach ($arr as $rey) {
        //         if (empty($rey)) {
        //             continue;
        //         }
        //         // Split the data from the attendance record
        //         $data = explode("\t", $rey);

        //         // Assuming employee ID is the first field in the data
        //         $employee_id = $data[0] ?? 'Unknown';

        //         // Assuming timestamp is the second field in the data
        //         $timestamp = $data[1] ?? 'Unknown';
        //         $two = $data[2] ?? 'Unknown';
        //         // Log the event including employee_id, timestamp, and the request data
        //         Log::info('New Device scan event', [
        //             'employee_id' => $employee_id,
        //             'timestamp' => $timestamp,
        //             'method' => $data[3],
        //             'two' => $two,
        //         ]);
        //     }
        // }

        //DB::connection()->enableQueryLog();
        $content['url'] = json_encode($request->all());
        $content['data'] = $request->getContent();;
        DB::table('finger_log')->insert($content);
        try {
            // $post_content = $request->getContent();
            //$arr = explode("\n", $post_content);
            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
            //$tot = count($arr);
            $tot = 0;
            //operation log
            if ($request->input('table') == "OPERLOG") {
                // $tot = count($arr) - 1;
                foreach ($arr as $rey) {
                    if (isset($rey)) {
                        $tot++;
                    }
                }
                return "OK: " . $tot;
            }
            $attendanceRecords = [];
            //attendance
            foreach ($arr as $rey) {

                // $data = preg_split('/\s+/', trim($rey));
                $data = explode("\t", $rey);

                // Check if the expected index exists to avoid Undefined array key error
                $employee_id = $data[0] ?? 'Unknown';
                $timestamp = $data[1] ?? 'Unknown';
                $method = $data[3] ?? null;

                if ($employee_id == 'Unknown' || $timestamp == 'Unknown' || $method == null) {
                    continue;
                } else {
                    $q['sn'] = $request->input('SN');
                    $q['table'] = $request->input('table');
                    $q['stamp'] = $request->input('Stamp');
                    $q['employee_id'] = $employee_id;
                    $q['timestamp'] = $timestamp;
                    $q['status2'] = $method;
                    $q['created_at'] = now();
                    $q['updated_at'] = now();
                    DB::table('in_out_records')->insert($q);
                    $attendanceRecords[] = $q;
                    $tot++;
                }
            }
              
		 Log::info('Processed Attendance Record', [
                    'employee_id' => $employee_id,
                    'timestamp' => $timestamp,
                    'method' => $method,
                ]);
            if (count($attendanceRecords) > 0) {
                $response = Http::post("https://hrt.bluedreamgroup.com/api/receive-data", [
                    'table'   => 'new_in_out_records',
                    'records' => $attendanceRecords,
                ]);

                if ($response->status() == 200) {
                    Log::info("success store from cPanel", [
                        'http_code' => $response->status(),
                        'response'  => $response->body(),
                    ]);
                } else {
                    Log::info("failed to store in cpanel", [
                        'http_code' => $response->status(),
                        'response'  => $response->body(),
                    ]);
                }
            }

            return "OK: " . $tot;
        } catch (Throwable $e) {

            $data['error'] = $e;
            // DB::table('error_log')->insert($data);
            report($e);
            return "ERROR: " . $tot . "\n";
        }
    }
    public function test(Request $request)
    {
        $log['data'] = $request->getContent();
        DB::table('finger_log')->insert($log);
    }
    public function getrequest(Request $request)
    {
        // $r = "GET OPTION FROM: ".$request->SN."\nStamp=".strtotime('now')."\nOpStamp=".strtotime('now')."\nErrorDelay=60\nDelay=30\nResLogDay=18250\nResLogDelCount=10000\nResLogCount=50000\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nRealtime=1\nEncrypt=0";

        return "OK";
    }
}
