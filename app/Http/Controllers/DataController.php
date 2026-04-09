<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data;
use App\Models\Uid;
use App\Models\LogEntry;
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

            // Validasi UID dari tabel uids
            $uidRecord = Uid::where('uid', $payload['uid'])->first();
            if (!$uidRecord) {
                return response()->json(['message' => 'Invalid UID'], 401);
            }

            // Validasi data dengan Laravel Validator
            $validator = Validator::make($payload, [
                'uid'   => 'required|string|exists:uids,uid',
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
            data::insert($insertData); // Batch insert untuk efisiensi

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['message' => 'Invalid token format', 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing request', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * POST /api/log
     * Terima log text dari logger.
     *
     * Body (JSON):
     * {
     *   "uid":     "ABC123",       // wajib — harus terdaftar di tabel uids
     *   "key":     "sparing",      // wajib — API key
     *   "level":   "INFO",         // opsional: INFO | WARNING | ERROR | DEBUG
     *   "message": "[SIM] ...",    // wajib — isi pesan log
     *   "logged_at": "2025-04-09 05:32:00"  // opsional — default: waktu server
     * }
     */
    public function postLog(Request $request)
    {
        $apiKey = config('app.logger_key', 'sparing');

        // Validasi API key
        if ($request->input('key') !== $apiKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'uid'       => 'required|string|exists:uids,uid',
            'message'   => 'required|string',
            'level'     => 'nullable|string|in:INFO,WARNING,ERROR,DEBUG',
            'logged_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        LogEntry::create([
            'uid'       => $request->uid,
            'level'     => strtoupper($request->input('level', 'INFO')),
            'message'   => $request->message,
            'logged_at' => $request->input('logged_at', now()),
        ]);

        return response()->json(['message' => 'Log saved'], 200);
    }

    /**
     * GET /api/logs?uid=xxx&limit=50&after_id=0
     * Ambil log terbaru untuk uid tertentu (digunakan dashboard polling).
     */
    public function getLogs(Request $request)
    {
        $uid     = $request->query('uid');
        $limit   = min((int) $request->query('limit', 50), 200);
        $afterId = (int) $request->query('after_id', 0);

        if (!$uid) {
            return response()->json([]);
        }

        try {
            if ($afterId > 0) {
                $logs = LogEntry::where('uid', $uid)
                    ->where('id', '>', $afterId)
                    ->orderBy('id', 'asc')
                    ->get(['id', 'level', 'message', 'logged_at']);
            } else {
                $logs = LogEntry::where('uid', $uid)
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->get(['id', 'level', 'message', 'logged_at'])
                    ->reverse()
                    ->values();
            }

            return response()->json($logs);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testConnection(Request $request)
    {
        return response()->json(['message' => 'Connection test successful']);
    }

    public function getData(Request $request)
    {
        $data = data::when($request->has('uid'), function ($query) use ($request) {
            $query->where('uid', $request->query('uid'));
        })->get();

        return response()->json($data);
    }

    public function getDataByUid($uid)
    {
        $data = data::where('uid', $uid)->get();
        return response()->json($data);
    }

    public function getRegisteredUids()
    {
        $uids = Uid::pluck('uid');
        return response()->json($uids);
    }
}
