<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $jmlkaryawan = User::where('role', '!=', 'admin');

        $isAdminAccess = false;

        if ($user->role == 'admin') {
            $isAdminAccess = true;
        }

        $jmlHadir = 0;
        $jmlkaryawan = 0;

        return view('admin.dashboard', ['jmlHadir' => $jmlHadir, 'jmlkaryawan' => $jmlkaryawan, 'isAdminAccess' => $isAdminAccess]);
    }
}
