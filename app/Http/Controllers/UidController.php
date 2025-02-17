<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uid;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan daftar UID
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $uids = Uid::all(); // Admin melihat semua UID
        } else {
            $uids = Uid::where('user_id', $user->id)->get(); // User hanya melihat UID mereka sendiri
        }
        
        return view('uid.index', compact('uids'));
    }

    // Hanya Admin yang bisa membuat UID baru
    public function create()
    {
        $this->authorize('isAdmin'); // Pastikan hanya admin yang bisa akses
        $users = User::all();
        return view('uid.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $request->validate([
            'uid' => 'required|string',
            'lokasi' => 'required|string'
        ]);

        Uid::create([
            'uid' => $request->uid,
            'user_id' => $request->user_id,
            'lokasi' => $request->lokasi
        ]);

        return redirect()->route('uids.index')->with('success', 'UID berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->authorize('isAdmin');

        $uid = Uid::findOrFail($id);
        return view('uid.edit', compact('uid'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('isAdmin');

        $request->validate([
            'uid' => 'required|string',
            'lokasi' => 'required|string'
        ]);

        $uid = Uid::findOrFail($id);
        $uid->update([
            'uid' => $request->uid,
            'lokasi' => $request->lokasi
        ]);

        return redirect()->route('uids.index')->with('success', 'UID berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        Uid::destroy($id);
        return redirect()->route('uids.index')->with('success', 'UID berhasil dihapus.');
    }
}
