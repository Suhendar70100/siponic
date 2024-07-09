<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Garden;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GardenController extends Controller
{
    public function index()
    {
        return view('garden.index');
    }

    public function dataTable(): JsonResponse
    {
        $data = Garden::query()->get();

        return DataTables::of($data)
        ->addColumn('aksi', function ($row) {
            return " <a href='#' data-id='$row->id' class='mdi mdi-pencil text-warning btn-edit'></a>
                            <a href='#' data-id='$row->id' class='mdi mdi-trash-can text-primary btn-delete'></a>";
        })
        ->rawColumns(['aksi'])
        ->toJson();
    }

    public function store(Request $request): JsonResponse
{
    try {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:garden,name', 'min:3'],
            'address' => ['required', 'min:3'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $garden = Garden::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'garden_id' => $garden->id,
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
        $garden = Garden::with('users')->find($id);

        if (!$garden) {
            return response()->json(['message' => 'Data perumahan tidak ditemukan'], 404);
        }

        return response()->json($garden);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('garden', 'name')->ignore($id), 'min:3'],
                'address' => ['required', 'min:3'],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id, 'garden_id'),
                ],
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $garden = Garden::findOrFail($id);
            $garden->update([
                'name' => $request->name,
                'address' => $request->address,
            ]);
    
            $user = User::where('garden_id', $id)->firstOrFail();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
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
        $garden = Garden::find($id);

        if (!$garden) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $garden->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }

}