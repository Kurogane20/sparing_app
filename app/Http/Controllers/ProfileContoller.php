<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class ProfileContoller extends Controller
{
    public function index(){
      $user = auth()->user();
      return view('profile', compact('user'));
    }
}
