<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = 'active';
        $user->company = $request->company;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->uid = $request->uid;
        $user->companytype = $request->company_type;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id){
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            // 'email' => 'required|email|unique:users',
            // 'password' => 'required|min:8',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->status = $request->status;
        $user->company = $request->company;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->uid = $request->uid;
        $user->companytype = $request->company_type;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    
}
