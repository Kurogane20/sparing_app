<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function getKey()
    {
        $key = 'sparing';
        return response($key, 200)->header('Content-Type', 'text/plain');
    }

    public function postData(Request $request)
    {
        $key = 'sparing';

        try {
            // Ambil token dari request
            $token = $request->json()->get('token');
            if (!$token) {
                return response()->json(['message' => 'Token is required'], 400);
            }

            // Dekode JWT
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $payload = json_decode(json_encode($decoded), true); // Convert ke array


            // Validasi UID
            $user = User::where('uid', $payload['uid'])->first();
            if (!$user) {
                return response()->json(['message' => 'Invalid UID'], 401);
            }

            // Validasi data dengan Laravel Validator
            $validator = Validator::make($payload, [
                'uid'   => 'required|string|exists:users,uid',
                'data'  => 'required|array|min:1|max:30',
                'data.*.datetime' => 'required|integer',
                'data.*.pH'       => 'required|numeric|min:0|max:14',
                'data.*.cod'      => 'required|numeric|min:0',
                'data.*.tss'      => 'required|numeric|min:0',
                'data.*.nh3n'     => 'required|numeric|min:0',
                'data.*.debit'    => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid data format', 'errors' => $validator->errors()], 400);
            }

            // Simpan semua data dalam satu query (lebih efisien)
            $insertData = [];
            foreach ($payload['data'] as $sensorData) {
                $insertData[] = [
                    'uid'      => $payload['uid'],
                    'datetime' => $sensorData['datetime'],
                    'ph'       => $sensorData['pH'],
                    'cod'      => $sensorData['cod'],
                    'tss'      => $sensorData['tss'],
                    'nh3n'     => $sensorData['nh3n'],
                    'debit'    => $sensorData['debit'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Data::insert($insertData); // Batch insert untuk efisiensi

            return response()->json(['message' => 'Data saved successfully'], 200);

        } catch (\UnexpectedValueException $e) {
            return response()->json(['message' => 'Invalid token format', 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing request', 'error' => $e->getMessage()], 500);
        }
    }

    public function testConnection(Request $request)
    {
        return response()->json(['message' => 'Connection test successful']);
    }

    public function getData(Request $request)
    {
        $data = Data::when($request->has('uid'), function ($query) use ($request) {
            $query->where('uid', $request->query('uid'));
        })->get();

        return response()->json($data);
    }

    public function getDataByUid($uid)
    {
        $data = Data::where('uid', $uid)->get();
        return response()->json($data);
    }

    public function getRegisteredUids()
    {
        $uids = User::whereNotNull('uid')->pluck('uid');
        return response()->json($uids);
    }
}
