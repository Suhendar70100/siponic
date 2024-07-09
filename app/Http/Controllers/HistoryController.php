<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    public function index()
    {
        return view('history.index');
    }

    public function dataTable(Request $request)
    {
        $query = Sensor::with('device');

        if (Auth::user()->usertype !== 'admin') {
            $query->whereHas('device', function ($query) {
                $query->where('garden_id', Auth::user()->garden_id);
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $startDateFormatted = Carbon::parse($startDate)->startOfDay();
            $endDateFormatted = Carbon::parse($endDate)->endOfDay();
            
            $query->whereBetween('send_at', [$startDateFormatted, $endDateFormatted]);
        }

        return DataTables::of($query)
            ->addColumn('device_guid', function (Sensor $sensor) {
                return $sensor->device ? $sensor->device->guid : 'N/A';
            })
            ->make(true);
    }

}
