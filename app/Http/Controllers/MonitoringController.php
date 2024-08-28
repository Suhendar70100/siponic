<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Device;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MonitoringController extends Controller
{
    public function index()
    {
        $devices = Device::where('garden_id', Auth::user()->garden_id)
        ->where('status', 1)
        ->get();
        
        if (Auth::user()->usertype == 'admin') {
            $devices = Device::where('status', 1)->get();
        }
        return view('monitoring.index', compact('devices'));
    }

    public function getDailyAverages(Request $request)
{
    $deviceId = $request->input('device_id');
    $month = $request->input('month'); 
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    $sensors = Sensor::where('device_id', $deviceId)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('AVG(water_ph) as avg_water_ph'),
            DB::raw('AVG(temperature) as avg_temperature'),
            DB::raw('AVG(humidity) as avg_humidity'),
            DB::raw('AVG(ppm) as avg_ppm')
        )
        ->groupBy('date')
        ->get();

    $data = [
        'dates' => [],
        'water_ph' => [],
        'temperature' => [],
        'humidity' => [],
        'ppm' => [],
    ];

    $totalWaterPh = 0;
    $totalTemperature = 0;
    $totalHumidity = 0;
    $totalPpm = 0;
    $totalDaysWithData = 0;

    for ($day = 1; $day <= $endOfMonth->day; $day++) {
        $date = $startOfMonth->copy()->day($day)->format('Y-m-d');
        $dailyData = $sensors->firstWhere('date', $date);

        $data['dates'][] = (string) $day;
        $data['water_ph'][] = $dailyData->avg_water_ph ?? 0;
        $data['temperature'][] = $dailyData->avg_temperature ?? 0;
        $data['humidity'][] = $dailyData->avg_humidity ?? 0;
        $data['ppm'][] = $dailyData->avg_ppm ?? 0;

        if ($dailyData) {
            $totalWaterPh += $dailyData->avg_water_ph;
            $totalTemperature += $dailyData->avg_temperature;
            $totalHumidity += $dailyData->avg_humidity;
            $totalPpm += $dailyData->avg_ppm;
            $totalDaysWithData++;
        }
    }

    $data['periode'][] = date('F Y', strtotime($month));

    // Calculate the monthly averages
    $monthlyAverages = [
        $totalDaysWithData ? $totalPpm / $totalDaysWithData : 0,
        $totalDaysWithData ? $totalWaterPh / $totalDaysWithData : 0,
        $totalDaysWithData ? $totalTemperature / $totalDaysWithData : 0,
        $totalDaysWithData ? $totalHumidity / $totalDaysWithData : 0,
    ];

    // Add the monthly averages to the response data
    $data['monthly_averages'] = $monthlyAverages;

    return $data;
}


}
