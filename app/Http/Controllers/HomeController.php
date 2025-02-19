<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data;
use Illuminate\Http\Request;
use App\Models\Uid;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 'admin') {
            // Ambil UID distinct sebagai object
            $uids = Data::select('uid')->distinct()->get();
            $selectUid = $request->input('uid', $uids->first()->uid ?? null);
        } else {
            // Ambil UID sebagai string, lalu ubah menjadi object
            $uids = $user->uids->pluck('uid')->map(function ($uid) {
                return (object) ['uid' => $uid];
            });
            $selectUid = $request->input('uid', $uids->first()->uid ?? null);
        }

        // Ambil data terbaru dan data recent
        $latestData = Data::where('uid', $selectUid)->orderBy('datetime', 'desc')->first();
        $recentData = Data::where('uid', $selectUid)->orderBy('datetime', 'desc')->take(10)->get();

        return view('home', compact('latestData', 'uids', 'selectUid', 'recentData'));
    }

    public function getLatestData(Request $request)
    {
        $user = auth()->user();
        $uid = $request->query('uid');

        // Jika user bukan admin, pastikan UID yang diminta miliknya
       if ($user->role != 'admin' && !$user->uids()->where('uid', $uid)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $latestData = Data::where('uid', $uid)->orderBy('datetime', 'desc')->first();

        if ($latestData) {
            return response()->json([
                'pH' => $latestData->pH,
                'tss' => $latestData->tss,
                'cod' => $latestData->cod,
                'nh3n' => $latestData->nh3n,
                'debit' => $latestData->debit,
            ]);
        }

        return response()->json([
            'pH' => 'N/A',
            'tss' => 'N/A',
            'cod' => 'N/A',
            'nh3n' => 'N/A',
            'debit' => 'N/A',
        ]);
    }
}
