<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InformationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->usertype == "admin") {
            $device = Device::query()->get();
        } else {
            $device = Device::query()->where('id', $user->garden_id)->get();
        }

        return view('information.index', compact('device'));
    }

    public function dataTable(): ?JsonResponse
    {
        $user = Auth::user();

        if ($user->usertype == "admin") {
            $data = Information::with('device')->get();
        } else {
            $data = Information::whereHas('device', function ($query) use ($user) {
                $query->where('garden_id', $user->garden_id);
            })->with('device')->get();
        }

        return DataTables::of($data)
            ->addColumn('note', function ($row) {
                return $row->device->note;
            })
            ->addColumn('aksi', function ($row) {
                return "<a href='#' data-id='$row->id' class='mdi mdi-pencil text-warning btn-edit'></a>
                    <a href='#' data-id='$row->id' class='mdi mdi-trash-can text-primary btn-delete'></a>";
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }


    public function store(Request $request): JsonResponse
{
    try {
        $validator = Validator::make($request->all(), [
            'device_id' => ['required'],
            'seeding_start_date' => ['required', 'date'],
            'harvest_date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Information::create([
            'device_id' => $request->device_id,
            'seeding_start_date' => $request->seeding_start_date,
            'harvest_date' => $request->harvest_date,
            'harvest_yield' => $request->harvest_yield,
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan'
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
    }
}

    public function show($id): JsonResponse
    {
        $information = Information::find($id);

        if (!$information) {
            return response()->json(['message' => 'Data perangkat tidak ditemukan'], 404);
        }

        return response()->json($information);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'device_id' => ['required'],
                'seeding_start_date' => ['required', 'date'],
                'harvest_date' => ['required', 'date'],
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $information = Information::findOrFail($id);
            $information->update([
                'device_id' => $request->device_id,
                'seeding_start_date' => $request->seeding_start_date,
                'harvest_date' => $request->harvest_date,
                'harvest_yield' => $request->harvest_yield,
            ]);
    
            return response()->json([
                'message' => 'Data berhasil diperbarui'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data'], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        $information = Information::find($id);

        if (!$information) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $information->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
