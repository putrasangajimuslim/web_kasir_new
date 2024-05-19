<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index() {
        $user = Auth::user();

        return view('admin.user-profile.index', ['user' => $user]);
    }

    public function updateProfile(Request $request) {
        $validateData = $request->validate([
            'email' => 'required|email',
            'birthday' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $user = Auth::user();
        $user = User::where('id', $user->id)->first();
        $user->email = $request->email;
        $user->tgl_lahir = $request->birthday;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('user-profile.index')->with('message', 'Berhasil Update Profile');
    }
}
