<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Device;
use App\Models\Garden;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpMqtt\Client\MqttClient;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DeviceCreateRequest;
use App\Http\Requests\DeviceUpdateRequest;
use PhpMqtt\Client\Exceptions\MqttClientException;

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

            $device = Device::create($data);

            $mqttData = [
                'device_id' => $device->id,
                'min_ppm' => $device->min_ppm,
                'max_ppm' => $device->max_ppm,
            ];

            Log::info('Device created:', $mqttData);

            $this->sendDataToMqtt($mqttData);

            return response()->json(['message' => 'Data berhasil ditambahkan'], 201);
        } catch (Exception $e) {
            Log::error('Error adding device: ' . $e->getMessage());
            return response()->json(['message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
        }
    }

    protected function sendDataToMqtt(array $data)
{
    $host = env('MQTT_HOST', 'broker.hivemq.com');
    $port = env('MQTT_PORT', 1883);
    $clientId = env('MQTT_CLIENT_ID', 'laravel-client');
    $username = trim(env('MQTT_USERNAME', ''));
    $password = env('MQTT_PASSWORD', '');

    Log::info("Connecting to MQTT broker at {$host}:{$port} with client ID {$clientId}");
    Log::info("Using username: '{$username}'");

    $client = new MqttClient($host, $port, $clientId);

    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
        ->setKeepAliveInterval(60)
        ->setConnectTimeout(5)
        ->setMaxReconnectAttempts(3)
        ->setLastWillTopic('siponic/device/disconnect')
        ->setLastWillMessage(json_encode($data))
        ->setLastWillQualityOfService(0);

    if (!empty($username)) {
        $connectionSettings->setUsername($username);
        if (!empty($password)) {
            $connectionSettings->setPassword($password);
        }
    }

    try {
        $client->connect($connectionSettings, true);
        Log::info('Connected to MQTT broker');

        $client->publish('siponic/device/setPoint', json_encode($data), 0);
        Log::info('Data published to MQTT', $data);

        sleep(2);

        $client->disconnect();
        Log::info('Disconnected from MQTT broker');
    } catch (MqttClientException $e) {
        Log::error('MQTT Client Exception: ' . $e->getMessage());
    } catch (Exception $e) {
        Log::error('Error sending data to MQTT: ' . $e->getMessage());
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

            $mqttData = [
                'device_id' => $device->id,
                'min_ppm' => $device->min_ppm,
                'max_ppm' => $device->max_ppm,
            ];

            Log::info('Device updated:', $mqttData);

            $this->sendDataToMqtt($mqttData);

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