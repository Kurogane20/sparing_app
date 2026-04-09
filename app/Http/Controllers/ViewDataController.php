<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data;
use App\Models\Uid;
use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\DataExceededThreshold;

class ViewDataController extends Controller
{
    private $thresholds = [
        'pH' => ['min' => 6, 'max' => 9],
        'cod' => ['min' => 0, 'max' => 1000],
        'tss' => ['min' => 0, 'max' => 70],
        'nh3n' => ['min' => 0, 'max' => 1000],
        'debit' => ['min' => 0, 'max' => 500]
    ];

    public function index(Request $request)
    {
        $user = auth()->user();
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
        $sortable = ['created_at', 'datetime', 'pH', 'cod', 'tss', 'nh3n', 'debit'];

        // Pastikan kolom yang digunakan valid
        if (!in_array($sort, $sortable)) {
            $sort = 'created_at';
        }

        // Query data sesuai dengan role user
        if ($user->role == 'admin') {
            $data = data::orderBy($sort, $order)->paginate(10);
        } else {
            // Ambil UID dari relasi
            $uids = $user->uids->pluck('uid');
            $data = data::whereIn('uid', $uids)
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
            $data = $requestedUid ? data::where('uid', $requestedUid)->get() : data::all();
        } else {
            $data = data::whereIn('uid', function ($query) use ($user) {
                $query->select('uid')->from('uids')->where('user_id', $user->id);
            })->get();
        }

        return response()->json($data);
    }

    public function getStats(Request $request)
    {
        $user = auth()->user();
        $requestedUid = $request->input('uid');

        if ($user->role == 'admin') {
            $data = $requestedUid ? data::where('uid', $requestedUid)->get() : data::all();
        } else {
            $data = data::whereIn('uid', function ($query) use ($user) {
                $query->select('uid')->from('uids')->where('user_id', $user->id);
            })->get();
        }

        $totalData = $data->count();
        $validData = 0;
        $invalidData = 0;

        foreach ($data as $item) {
            $isValid = true;
            foreach ($this->thresholds as $key => $threshold) {
                if (isset($item[$key]) && ($item[$key] < $threshold['min'] || $item[$key] > $threshold['max'])) {
                    $isValid = false;
                    if (!$item->notification_sent) {
                        event(new DataExceededThreshold("Nilai $key melebihi batas."));
                        $item->notification_sent = true;
                        $item->save();
                    }
                    break;
                }
            }
            $isValid ? $validData++ : $invalidData++;
        }

        return response()->json(['stats' => ['total' => $totalData, 'valid' => $validData, 'invalid' => $invalidData]]);
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $requestedUid = $request->input('uid');

        $query = data::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($user->role != 'admin') {
            $query->whereIn('uid', function ($query) use ($user) {
                $query->select('uid')->from('uids')->where('user_id', $user->id);
            });
        } elseif ($requestedUid) {
            $query->where('uid', $requestedUid);
        }

        return Excel::download(new DataExport($query->get()), 'data_export_' . now()->format('Ymd_His') . '.xlsx');
    }
}
