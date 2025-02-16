<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data;
use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\DataExceededThreshold;

class ViewDataController extends Controller
{
    // Batas ambang (sesuaikan sesuai kebutuhan Anda)
    private $thresholds = [
        'pH' => ['min' => 6, 'max' => 9],
        'cod' => ['min' => 0, 'max' => 1000],
        'tss' => ['min' => 0, 'max' => 300],
        'nh3n' => ['min' => 0, 'max' => 1000],
        'debit' => ['min' => 0, 'max' => 500]
    ];

    public function index(Request $request)
    {
         $user = auth()->user();
    
        // Ambil parameter sorting dari request
        $sort = $request->query('sort', 'created_at'); // Default sorting berdasarkan 'created_at'
        $order = $request->query('order', 'desc'); // Default descending

        // Daftar kolom yang bisa digunakan untuk sorting
        $sortable = ['created_at', 'datetime', 'pH', 'cod', 'tss', 'nh3n', 'debit'];

        // Pastikan kolom yang digunakan valid
        if (!in_array($sort, $sortable)) {
            $sort = 'created_at';
        }

        // Query data sesuai dengan role user
        if ($user->role == 'admin') {
            $data = Data::orderBy($sort, $order)->paginate(10);
        } else {
            $data = Data::where('uid', $user->uid)
                        ->orderBy($sort, $order)
                        ->paginate(10);
        }

        return view('viewdata', [
            'data' => $data,
            'sort' => $sort,
            'order' => $order
        ]);
    }

    public function getData(Request $request)
    {
        $user = auth()->user();
        $requestedUid = $request->input('uid');

        if ($user->role == 'admin') {
            if ($requestedUid) {
                $data = Data::where('uid', $requestedUid)->get();
            } else {
                $data = Data::all();
            }
        } else {
            $data = Data::where('uid', $user->uid)->get();
        }

        return response()->json($data);
    }

    public function getStats(Request $request)
    {
        $user = auth()->user();
        $requestedUid = $request->input('uid');

        if ($user->role == 'admin') {
            if ($requestedUid) {
                $data = Data::where('uid', $requestedUid)->get();
            } else {
                $data = Data::all();
            }
        } else {
            $data = Data::where('uid', $user->uid)->get();
        }

        // Statistik untuk pie chart
        $totalData = $data->count();
        $validData = 0;
        $invalidData = 0;

        foreach ($data as $item) {
            $isValid = true;
            foreach ($this->thresholds as $key => $threshold) {
                if (isset($item[$key])) {
                    if ($item[$key] < $threshold['min'] || $item[$key] > $threshold['max'])
                     {
                        $isValid = false;

                        // Periksa apakah notifikasi sudah dikirim
                        if (!$item->notification_sent) {
                            // Kirim event jika data melebihi ambang batas
                            event(new DataExceededThreshold("Nilai $key melebihi batas yang ditentukan."));

                            // Tandai notifikasi sudah dikirim
                            $item->notification_sent = true;
                            $item->save();
                        }
                        break;
                    }
                }
            }
            if ($isValid) {
                $validData++;
            } else {
                $invalidData++;
            }
        }

        return response()->json([
            'stats' => [
                'total' => $totalData,
                'valid' => $validData,
                'invalid' => $invalidData
            ]
        ]);
    }
    public function export(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $requestedUid = $request->input('uid');

        $query = Data::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($user->role != 'admin') {
            $query->where('uid', $user->uid);
        } elseif ($requestedUid) {
            $query->where('uid', $requestedUid);
        }

        $data = $query->get();

        return Excel::download(new DataExport($data), 'data_export_' . now()->format('Ymd_His') . '.xlsx');
    }
}
