<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::get();
        return view('admin.users.index', ['users' => $users]);
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $validateData = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'tgl_lahir' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'role' => 'required',
            'password' => 'required',
       ]);

       $checkNewUsers = User::where('nama', $request->nama)
                                ->where('email', $request->email)
                                ->where('tgl_lahir', $request->tgl_lahir)
                                ->first();

       if (!empty($checkNewUsers)) {
         return redirect()->back()->with('error', 'Maaf User Baru Tersebut sudah ada');
       }

       $tgl_lahir = new DateTime($request->tgl_lahir);

       $bulan_lahir = $tgl_lahir->format('m');
       $tahun_lahir = $tgl_lahir->format('Y');
       $kodeKaryawan = $bulan_lahir . $tahun_lahir; 

       $userCount = User::count();
       $sequenceNumber = $userCount + 1;
       $kodeKaryawan = str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT) . $bulan_lahir . $tahun_lahir;

       $users = new User();
        $users->kode_karyawan = $kodeKaryawan;
        $users->nama = $request->nama;
        $users->email = $request->email;
        $users->password = bcrypt($request->password);
        $users->tgl_lahir = $request->tgl_lahir;
        $users->status = $request->status;
        $users->no_hp = $request->no_hp;
        $users->alamat = $request->alamat;
        $users->jenis_kelamin = $request->jenis_kelamin;
        $users->role = $request->role;
        $users->status = 1;
        $users->save();

        return redirect()->back()->with('message', 'Berhasil Tambah Users');
    }

    public function edit($id) {
        $user = User::where('id', $id)->first();
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request) {
        $validateData = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'tgl_lahir' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'role' => 'required',
            'status' => 'required',
       ]);

       $id = $request->id_users;
       $nama = $request->nama;
       $email = $request->email;
       $tglLahir = $request->tgl_lahir;
       $noHp = $request->no_hp;
       $alamat = $request->alamat;
       $gender = $request->alamat;
       $password = $request->password;
       $role = $request->role;
       $status = $request->status;

       $absen = User::where('id', $id)->first();
       $absen->nama = $nama;
       $absen->email = $email;
       $absen->tgl_lahir = $tglLahir;
       $absen->no_hp = $noHp;
       $absen->alamat = $alamat;
       $absen->jenis_kelamin = $gender;
       $absen->password = !empty($password) ? bcrypt($password) : '';
       $absen->role = $role;
       $absen->status = $status;
       $absen->save();

       return redirect()->route('users.index')->with('message', 'Berhasil Update User ' . $nama);
    }

    public function activation_account($id) {
        $user = User::where('id', $id)->first();
        $user->status = 1;
        $user->save();

        return redirect()->back()->with('message', 'Berhasil Aktivasi Users');
    }

    public function destroy($id) {
        User::find($id)->delete();

        return redirect()->route('users.index')->with('message', 'Berhasil Delete Users');
    }
}
