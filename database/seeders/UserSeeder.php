<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataUserOld = [
            ["nama" => 'Indah Khaerunnisa SH', 'tgl_lahir' => '1980-01-10', 'status' => 1, 'no_hp' => '087877787656', 'alamat' => 'jl.Tebet raya', 'jenis_kelamin' => 'P', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'indah@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'admin', 'tgl_lahir' => '1999-01-22', 'status' => 1, 'no_hp' => '0828182112', 'alamat' => 'jl.kembang', 'jenis_kelamin' => 'L', 'password' => bcrypt('password'), 'role' => 'admin', 'email' => 'admin@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'Fadlur Rahman', 'tgl_lahir' => '1988-06-22', 'status' => 1, 'no_hp' => '085782455676', 'alamat' => 'jl.Cilodong Raya', 'jenis_kelamin' => 'L', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'fadlur@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'Widia Salsabila', 'tgl_lahir' => '1990-04-11', 'status' => 1, 'no_hp' => '089577780987', 'alamat' => '', 'jenis_kelamin' => 'P', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'widia@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'Sofyan Ahmad', 'tgl_lahir' => '1995-05-02', 'status' => 1, 'no_hp' => '08788895643', 'alamat' => '', 'jenis_kelamin' => 'L', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'sofyan@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'Andre', 'tgl_lahir' => '1997-03-02', 'status' => 1, 'no_hp' => '08281221214', 'alamat' => '', 'jenis_kelamin' => 'L', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'andre@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ["nama" => 'Parto', 'tgl_lahir' => '1997-01-02', 'status' => 1, 'no_hp' => '08214431121', 'alamat' => '', 'jenis_kelamin' => 'L', 'password' => bcrypt('password'), 'role' => 'kasir', 'email' => 'parto@gmail.com', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        $newUser = [];
        
        $start = 1;
        
        for ($i = 0; $i < count($dataUserOld); $i++) {
            if ($dataUserOld[$i]['nama'] == 'admin') {
                $kodeKaryawan = 'admin';
            } else {
                $tglLahir = Carbon::parse($dataUserOld[$i]['tgl_lahir']);
                $tahun = $tglLahir->format('Y');
                $bulan = $tglLahir->format('m');
                $kodeKaryawan = $bulan . $tahun;
            }
        
            $newUser[] = [
                'kode_karyawan' => $kodeKaryawan,
                'nama' => $dataUserOld[$i]['nama'],
                'tgl_lahir' => $dataUserOld[$i]['tgl_lahir'],
                'status' => $dataUserOld[$i]['status'],
                'no_hp' => $dataUserOld[$i]['no_hp'],
                'alamat' => $dataUserOld[$i]['alamat'],
                'jenis_kelamin' => $dataUserOld[$i]['jenis_kelamin'],
                'password' => $dataUserOld[$i]['password'],
                'role' => $dataUserOld[$i]['role'],
                'email' => $dataUserOld[$i]['email'],
                'created_at' => $dataUserOld[$i]['created_at'],
                'updated_at' => $dataUserOld[$i]['updated_at'],
            ];
        }

        DB::table('users')->insert($newUser);
    }
}
