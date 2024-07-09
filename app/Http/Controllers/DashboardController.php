<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Device;
use App\Models\Sensor;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $user = Auth::user();

        $devices = Device::where('garden_id', $user->garden_id)
            ->where('status', 1)
            ->get();
            $info = Information::whereHas('device', function ($query) use ($user) {
                $query->where('garden_id', $user->garden_id);
            })->whereNull('harvest_yield')->with('device')->get()->map(function ($item) {
                $item->seeding_start_date = Carbon::parse($item->seeding_start_date)->translatedFormat('j F Y');
                $item->harvest_date = Carbon::parse($item->harvest_date)->translatedFormat('j F Y');
                return $item;
            });
            

        if ($user->usertype == 'admin') {
            $devices = Device::where('status', 1)->get();
            $info = Information::with('device')->whereNull('harvest_yield')->get()->map(function ($item) {
                $item->seeding_start_date = Carbon::parse($item->seeding_start_date)->translatedFormat('j F Y');
                $item->harvest_date = Carbon::parse($item->harvest_date)->translatedFormat('j F Y');
                return $item;
            });
            
        }

        return view('dashboard.index', compact('devices', 'info'));
    }

    public function realtimeData(Request $request)
    {
        $deviceId = $request->input('device_id');

        $latestSensorData = Sensor::where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->first();

        $device = Device::find($deviceId);

        return response()->json([
            'water_ph' => $latestSensorData->water_ph,
            'temperature' => $latestSensorData->temperature,
            'humidity' => $latestSensorData->humidity,
            'ppm' => $latestSensorData->ppm,
            'max_ppm' => $device->max_ppm,
            'min_ppm' => $device->min_ppm,
        ]);
    }
}
