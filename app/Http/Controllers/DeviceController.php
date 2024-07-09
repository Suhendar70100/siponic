<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Device;
use App\Models\Garden;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DeviceCreateRequest;
use App\Http\Requests\DeviceUpdateRequest;

class DeviceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->usertype == "admin") {
            $garden = Garden::query()->get();
        } else {
            $garden = Garden::query()->where('id', $user->garden_id)->get();
        }

        return view('device.index', compact('garden'));
    }

    public function dataTable(): ?JsonResponse
    {
        $user = Auth::user();

        $query = Device::query()->with('garden');

        if ($user->usertype == "admin") {
            $data = $query->get();
        } else {
            $data = $query->where('garden_id', $user->garden_id)->get();
        }

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                return " <a href='#' data-id='$row->id' class='mdi mdi-pencil text-warning btn-edit'></a>
                                <a href='#' data-id='$row->id' class='mdi mdi-trash-can text-primary btn-delete'></a>";
            })
            ->addColumn('garden', function (Device $device) {
                return $device->garden->name;
            })
            ->editColumn(name: 'status', content: function ($row) {
                $checked = $row->status === 1 ? 'checked' : '';

                return "<label class='switch switch-primary'>
                            <input type='checkbox' data-id='$row->id' class='switch-input switch-status' $checked  >
                            <span class='switch-toggle-slider'>
                              <span class='switch-on'></span>
                              <span class='switch-off'></span>
                            </span>                            </label>";
            })
            ->rawColumns(['aksi', 'status', 'garden'])
            ->toJson();
    }

    public function store(DeviceCreateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['guid'] = Str::upper($data['guid']);

            Device::query()->create($data);

            return response()->json(['message' => 'Data berhasil di tambahkan'], 201);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['message' => 'Data perangkat tidak ditemukan'], 404);
        }

        return response()->json($device);
    }

    public function update(DeviceUpdateRequest $request, $id): JsonResponse
    {
        try {
            $device = Device::find($id);
            $device->update($request->all());

            return response()->json(['message' => 'Perangkat berhasil di ubah']);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }

    public function status(Device $device): JsonResponse
    {

        try {

            $device->status = !$device->status == 1;

            $device->update((array)$device);

            return response()->json(['message' => 'Data berhasil di ubah'], 200);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }

    public function delete($id): JsonResponse
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $device->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }

}