<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Categories;
use App\Models\Kelola;
use App\Models\PotonganAlfa;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index() {
        $user = Auth::user();

        $isAdminAccess = false;

        $products = [];
    
        if ($user->role == 'admin') {
            $products = Products::all();
            // $kehadirans = Absensi::all();

            // foreach ($kehadirans as $kehadiran) {
            //     $jamMasuk = '';
            //     $jamKeluar = '';

            //     if (!empty($kehadiran->jam_masuk)) {
            //         $jamMasuk = Carbon::parse($kehadiran->jam_masuk)->format('H:i:s');
            //     }

            //     if (!empty($kehadiran->jam_keluar)) {
            //         $jamKeluar = Carbon::parse($kehadiran->jam_keluar)->format('H:i:s');
            //     }

            //     // $createdAt = $kehadiran->jam_masuk ? Carbon::parse($kehadiran->jam_masuk)->format('Y-m-d') : Carbon::parse($kehadiran->jam_keluar)->format('Y-m-d');
            //     $createdAt = $kehadiran->jam_masuk ? Carbon::parse($kehadiran->jam_masuk)->format('Y-m-d') : ($kehadiran->jam_keluar ? Carbon::parse($kehadiran->jam_keluar)->format('Y-m-d') : Carbon::parse($kehadiran->created_at)->format('Y-m-d'));

            //     $kehadiran->jam_masuk = $jamMasuk;
            //     $kehadiran->jam_keluar = $jamKeluar;
            //     $kehadiran->tgl = $createdAt;
            // }
            $isAdminAccess = true;
        } else {
            // $kodeKaryawan = $user->kode_karyawan;
            // $kehadirans = Absensi::where('kode_karyawan', $kodeKaryawan)->get();

            // foreach ($kehadirans as $kehadiran) {
            //     $jamMasuk = '';
            //     $jamKeluar = '';

            //     if (!empty($kehadiran->jam_masuk)) {
            //         $jamMasuk = Carbon::parse($kehadiran->jam_masuk)->format('H:i:s');
            //     }

            //     if (!empty($kehadiran->jam_keluar)) {
            //         $jamKeluar = Carbon::parse($kehadiran->jam_keluar)->format('H:i:s');
            //     }

            //     $createdAt = $kehadiran->jam_masuk ? Carbon::parse($kehadiran->jam_masuk)->format('Y-m-d') : ($kehadiran->jam_keluar ? Carbon::parse($kehadiran->jam_keluar)->format('Y-m-d') : Carbon::parse($kehadiran->created_at)->format('Y-m-d'));

            //     $kehadiran->jam_masuk = $jamMasuk;
            //     $kehadiran->jam_keluar = $jamKeluar;
            //     $kehadiran->tgl = $createdAt;
            // }
        }

        return view('admin.barang.index', ['isAdminAccess' => $isAdminAccess, 'products' => $products]);
    }

    public function create() {
        $categories = Categories::all();

        return view('admin.barang.create', ['categories' => $categories]);
    }

    public function store(Request $request) {
    //     $validateData = $request->validate([
    //         'kode_karyawan' => 'required',
    //    ]);

    //    $month = date('m');
    //    $years = date('Y');

    //     $absen = new Absensi();
    //     $absen->kode_karyawan = $request->kode_karyawan;
    //     $absen->jam_masuk = $request->jam_masuk;
    //     $absen->jam_keluar = $request->jam_keluar;

    //     if (empty($request->keterangan)) {

    //         $absen->keterangan = 'hadir';

    //         $cekKelola = Kelola::where('kode_karyawan', $request->kode_karyawan)
    //                                 ->where('bulan', $month)
    //                                 ->where('tahun', $years)
    //                                 ->first();

    //         $user = User::where('kode_karyawan', $request->kode_karyawan)->with('jabatan')->first();
            
    //         if ($cekKelola) {
    //             // Data sudah ada, lakukan pembaruan
    //             $cekKelola->update([
    //                 'jml_kehadiran' => $cekKelola->jml_kehadiran + 1,
    //             ]);
    //         } else {
    //             // Data belum ada, lakukan penyisipan
    //             $gajiBersih = ($user->jabatan->gaji_pokok + $user->jabatan->tunjangan_transport - $user->jabatan->potongan);

    //             Kelola::create([
    //                 'kode_karyawan' => $request->kode_karyawan,
    //                 'bulan' => $month,
    //                 'tahun' => $years,
    //                 'jml_kehadiran' => 1,
    //                 'jml_alfa' => 0,
    //                 'gaji_pokok' => $user->jabatan->gaji_pokok,
    //                 'bonus' => $user->jabatan->bonus,
    //                 'tunjangan_transport' => $user->jabatan->tunjangan_transport,
    //                 'potongan' => $user->jabatan->potongan,
    //                 'gaji_bersih' => $gajiBersih,
    //             ]);
    //         }
    //     } else {
    //         $absen->keterangan = $request->keterangan;

    //         $kelola = Kelola::where('kode_karyawan', $request->kode_karyawan)
    //                             ->where('bulan', $month)
    //                             ->where('tahun', $years)
    //                             ->first();

    //         $user = User::where('kode_karyawan', $kelola->kode_karyawan)->first();

    //         $potonganAlfa = PotonganAlfa::where('id_jabatan', $user->id_jabatan)->first();

    //         if ($request->keterangan == 'alfa') {
                
    //             $accumulationAlfa = $kelola->gaji_bersih - intval($potonganAlfa->jml);
    //             $kelola->gaji_bersih = $accumulationAlfa;
    //             if (!empty($kelola->jml_alfa)) {
    //                 $jmlAlfaInt = intval($kelola->jml_alfa); // Menggunakan intval() untuk mengonversi string menjadi integer
    //                 $jmlAlfaInt++; 

    //                 $kelola->jml_alfa = $jmlAlfaInt;

    //             } else {
    //                 $kelola->jml_alfa = 1;
    //             }
                
    //             $kelola->save();
    //         }
    //     }

    //     $absen->save();

    //     return redirect()->back()->with('message', 'Berhasil Rekam Kehadiran');
    }

    public function edit($id) {
        // $kodekaryawans = User::get();
        // $absen = Absensi::where('id', $id)->first();

        // return view('admin.absen.edit', ['absen' => $absen, 'kodekaryawans' => $kodekaryawans]);
    }

    public function update(Request $request) {
        // $id = $request->id_absen;
        // $kodeKaryawan = $request->kode_karyawan;
        // $jamMasuk = $request->jam_masuk;
        // $jamKeluar = $request->jam_keluar;
        // $ket = $request->keterangan;
        // $month = date('m');
        // $years = date('Y');

        // if (empty($ket)) {
        //     $ket = 'hadir';

        //     $cekKelola = Kelola::where('kode_karyawan', $kodeKaryawan)
        //                             ->where('bulan', $month)
        //                             ->where('tahun', $years)
        //                             ->first();

        //     $user = User::where('kode_karyawan', $kodeKaryawan)->with('jabatan')->first();
            
        //     if ($cekKelola) {
        //         // Data sudah ada, lakukan pembaruan
        //         $cekKelola->update([
        //             'jml_kehadiran' => $cekKelola->jml_kehadiran + 1,
        //         ]);
        //     } else {
        //         // Data belum ada, lakukan penyisipan
        //         $gajiBersih = ($user->jabatan->gaji_pokok + $user->jabatan->tunjangan_transport - $user->jabatan->potongan);

        //         Kelola::create([
        //             'kode_karyawan' => $kodeKaryawan,
        //             'bulan' => $month,
        //             'tahun' => $years,
        //             'jml_kehadiran' => 1,
        //             'jml_alfa' => 0,
        //             'gaji_pokok' => $user->jabatan->gaji_pokok,
        //             'bonus' => $user->jabatan->bonus,
        //             'tunjangan_transport' => $user->jabatan->tunjangan_transport,
        //             'potongan' => $user->jabatan->potongan,
        //             'gaji_bersih' => $gajiBersih,
        //         ]);
        //     }
        // } else {
        //     $kelola = Kelola::where('kode_karyawan', $kodeKaryawan)
        //     ->where('bulan', $month)
        //     ->where('tahun', $years)
        //     ->first();

        //     $user = User::where('kode_karyawan', $kelola->kode_karyawan)->first();

        //     $potonganAlfa = PotonganAlfa::where('id_jabatan', $user->id_jabatan)->first();

        //     if ($ket == 'alfa') {
        //         $accumulation = ($kelola->gaji_bersih + $potonganAlfa->jml);
        //         $kelola->gaji_bersih = $accumulation;
        //         if (!empty($kelola->jml_alfa)) {
        //             $jmlAlfaInt = intval($kelola->jml_alfa); // Menggunakan intval() untuk mengonversi string menjadi integer
        //             $jmlAlfaInt++; 

        //             $kelola->jml_alfa = $jmlAlfaInt;
        //         } else {
        //             $kelola->jml_alfa = 1;
        //         }
        //     } else {
        //         $accumulation = ($kelola->gaji_bersih + $potonganAlfa->jml);
        //         if (!empty($kelola->jml_alfa)) {
        //             $jmlAlfaInt = intval($kelola->jml_alfa); // Menggunakan intval() untuk mengonversi string menjadi integer
        //             $jmlAlfaInt--; 

        //             $kelola->jml_alfa = $jmlAlfaInt;
        //         }
        //         $kelola->gaji_bersih = $accumulation;
        //         $kelola->jml_alfa = $jmlAlfaInt;
        //     }

        //     $kelola->save();
        // }

        // $absen = Absensi::where('id', $id)->first();
        // $absen->kode_karyawan = $kodeKaryawan;
        // $absen->jam_masuk = $jamMasuk;
        // $absen->jam_keluar = $jamKeluar;
        // $absen->keterangan = $ket;
        // $absen->save();

        // return redirect()->route('kehadiran.index')->with('message', 'Berhasil Update Absensi' . $kodeKaryawan);
    }

    public function cekKehadiran(Request $request) {

        $kodeKaryawan = $request->kode_karyawan;

        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();

        $absen = Absensi::where('kode_karyawan', $kodeKaryawan)->whereBetween('created_at', [$startDate, $endDate])->first();

        $cekKondisiJamMasuk = empty($absen->jam_masuk);
        $cekKondisiJamKeluar = empty($absen->jam_keluar);

        if ($kodeKaryawan === "admin") {
            $cekKondisiJamMasuk = false;
            $cekKondisiJamKeluar = false;
        }

        return response()->json([
            'cek_absen_masuk' => $cekKondisiJamMasuk,
            'cek_absen_keluar' => $cekKondisiJamKeluar,
        ]);
    }

    public function rekamKehadiran(Request $request) {

        $kodeKaryawan = $request->replace_kode_karyawan;
        $typeRekam = $request->type_rekam;
        $typeMessage = '';
        $month = date('m');
        $years = date('Y');

        $data = [
            'kode_karyawan' => $kodeKaryawan,
            'keterangan' => 'hadir'
        ];

        if ($typeRekam == 'clock_in') {
            $data['jam_masuk'] = now();
            $typeMessage = 'Clock In';

            $cekKelola = Kelola::where('kode_karyawan', $kodeKaryawan)
                                    ->where('bulan', $month)
                                    ->where('tahun', $years)
                                    ->first();

            $user = User::where('kode_karyawan', $kodeKaryawan)->with('jabatan')->first();
            
            if ($cekKelola) {
                // Data sudah ada, lakukan pembaruan
                $cekKelola->update([
                    'jml_kehadiran' => $cekKelola->jml_kehadiran + 1,
                ]);
            } else {
                // Data belum ada, lakukan penyisipan
                $gajiBersih = ($user->jabatan->gaji_pokok + $user->jabatan->tunjangan_transport - $user->jabatan->potongan);

                Kelola::create([
                    'kode_karyawan' => $kodeKaryawan,
                    'bulan' => $month,
                    'tahun' => $years,
                    'jml_kehadiran' => 1,
                    'jml_alfa' => 0,
                    'gaji_pokok' => $user->jabatan->gaji_pokok,
                    'bonus' => $user->jabatan->bonus,
                    'tunjangan_transport' => $user->jabatan->tunjangan_transport,
                    'potongan' => $user->jabatan->potongan,
                    'gaji_bersih' => $gajiBersih,
                ]);
            }
        } else {
            $data['jam_keluar'] = now();
            $typeMessage = 'Clock Out';
        }

        // Lakukan update atau insert
        Absensi::updateOrInsert(
            ['kode_karyawan' => $kodeKaryawan, 'created_at' => today()],
            $data
        );

        return redirect()->back()->with('message', 'Berhasil Rekam Kehadiran ' . $typeMessage);
    }

    public function destroy($id) {
        Absensi::find($id)->delete();

        return redirect()->route('kehadiran.index')->with('message', 'Berhasil Delete Absensi');
    }
}
