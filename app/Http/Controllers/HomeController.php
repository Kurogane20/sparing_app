<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data;
use Illuminate\Http\Request;

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
        // $users = User::all();
        if ($user->role == 'admin') {
            $uids = Data::select('uid')->distinct()->get();
            $selectUid = $request->input('uid', $uids->first()->uid ?? null);
            $latestData = Data::where('uid', $selectUid)->orderBy('datetime', 'desc')->first();
            $recentData = Data::where('uid', $selectUid)->orderBy('datetime', 'desc')->take(11)->get();
        } else {
            $uids = null;
            $selectUid = $user->uid;
            $latestData = Data::where('uid', $user->uid)->orderBy('datetime', 'desc')->first();
            $recentData = Data::where('uid', $user->uid)->orderBy('datetime', 'desc')->take(11)->get();
        }

        $widget = [
            // 'users' => $users,
            // 'data' => $data
            //...
        ];

        return view('home', compact('widget', 'latestData', 'uids', 'selectUid', 'recentData'));
    }

    public function getLatestData(Request $request)
    {
        $user = auth()->user();
        $uid = $request->query('uid');

        // Jika pengguna adalah admin, gunakan UID dari query parameter
        if ($user->role == 'admin') {
            $latestData = Data::where('uid', $uid)->orderBy('datetime', 'desc')->first();
        } 
        // Jika pengguna bukan admin, gunakan UID pengguna
        else {
            $uid = $user->uid;
            $latestData = Data::where('uid', $uid)->orderBy('datetime', 'desc')->first();
        }

        // Jika data ditemukan, kembalikan dalam format JSON
        if ($latestData) {
            return response()->json([
                'pH' => $latestData->pH,
                'tss' => $latestData->tss,
                'cod' => $latestData->cod,
                'nh3n' => $latestData->nh3n,
                'debit' => $latestData->debit,
            ]);
        }

        // Jika data tidak ditemukan, kembalikan nilai default
        return response()->json([
            'pH' => 'N/A',
            'tss' => 'N/A',
            'cod' => 'N/A',
            'nh3n' => 'N/A',
            'debit' => 'N/A',
        ]);
    }
}
