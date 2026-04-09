<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\data;
use Illuminate\Http\Request;
use App\Models\Uid;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Ambil semua Uid records sesuai role
        if ($user->role == 'admin') {
            $allUids = Uid::all();
        } else {
            $allUids = $user->uids;
        }

        // Daftar lokasi unik
        $lokasiList = $allUids->pluck('lokasi')->filter()->unique()->values();

        $selectLokasi = $request->input('lokasi', $lokasiList->first());

        // Tipe data yang tersedia untuk lokasi terpilih
        $tipeOptions = $allUids
            ->where('lokasi', $selectLokasi)
            ->pluck('tipe_data')
            ->unique()
            ->values();

        $selectTipe = $request->input('tipe_data', $tipeOptions->first());

        // Resolve lokasi + tipe_data → uid
        $matchedUid = $allUids
            ->where('lokasi', $selectLokasi)
            ->where('tipe_data', $selectTipe)
            ->first();

        $selectUid = $matchedUid ? $matchedUid->uid : null;

        // Data dashboard
        $latestData = $selectUid
            ? data::where('uid', $selectUid)->orderBy('datetime', 'desc')->first()
            : null;

        $recentData = $selectUid
            ? data::where('uid', $selectUid)->orderBy('datetime', 'desc')->take(10)->get()
            : collect();

        // Map uid ↔ lokasi ↔ tipe_data untuk JS client
        $uidMap = $allUids->map(fn($u) => [
            'uid'       => $u->uid,
            'lokasi'    => $u->lokasi,
            'tipe_data' => $u->tipe_data,
        ])->values();

        return view('home', compact(
            'latestData', 'recentData',
            'selectUid', 'selectLokasi', 'selectTipe',
            'lokasiList', 'tipeOptions', 'uidMap'
        ));
    }

    public function getLatestData(Request $request)
    {
        $user = auth()->user();
        $uid  = $request->query('uid');

        if ($user->role != 'admin' && !$user->uids()->where('uid', $uid)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $latestData = Data::where('uid', $uid)->orderBy('datetime', 'desc')->first();

        if ($latestData) {
            return response()->json([
                'pH'    => $latestData->pH,
                'tss'   => $latestData->tss,
                'cod'   => $latestData->cod,
                'nh3n'  => $latestData->nh3n,
                'debit' => $latestData->debit,
            ]);
        }

        return response()->json(['pH' => 'N/A', 'tss' => 'N/A', 'cod' => 'N/A', 'nh3n' => 'N/A', 'debit' => 'N/A']);
    }
}
