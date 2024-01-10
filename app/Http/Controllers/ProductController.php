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
    public function index()
    {
        $user = Auth::user();

        $isAdminAccess = false;

        $products = [];

        if ($user->role == 'admin') {
            $products = Products::with('kategori')->get();
            $isAdminAccess = true;
        }

        return view('admin.barang.index', ['isAdminAccess' => $isAdminAccess, 'products' => $products]);
    }

    public function create()
    {
        $categories = Categories::all();

        return view('admin.barang.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'kategori_id' => 'required',
            'merk' => 'required',
            'harga_beli' => 'required',
            'margin_keuntungan' => 'required',
            'satuan_barang' => 'required',
            'stok' => 'required',
        ]);

        $cekBarang = Products::where('nama_barang', $request->name)
            ->where('merk', $request->merk)
            ->first();

        $latestKodeBarang = Products::latest()->first();

        $kodeBarang = empty($latestKodeBarang) ? 'BR001' : $this->generateKodeBarang($latestKodeBarang->kode_barang);

        if (empty($cekBarang)) {
            $barang = new Products();
            $barang->kode_barang = $kodeBarang;
            $barang->kategori_id = $request->kategori_id;
            $barang->nama_barang = $request->name;
            $barang->merk = $request->merk;
            $barang->harga_beli = $request->harga_beli;
            $barang->harga_jual = $request->harga_jual;
            $barang->margin_keuntungan = $request->margin_keuntungan;
            $barang->satuan_barang = $request->satuan_barang;
            $barang->stok = $request->stok;
            $barang->save();

            return redirect()->route('products.index')->with('message', 'Berhasil Menyimpan Barang');
        } else {

            return redirect()->back()->with('error', 'Maaf Untuk Data Barang Tersebut Sudah Ada');
        }
    }

    public function edit($id)
    {
        $barang = Products::where('id', $id)->first();

        $categories = Categories::all();

        return view('admin.barang.edit', ['barang' => $barang, 'categories' => $categories]);
    }

    public function update(Request $request)
    {
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

        $validateData = $request->validate([
            'name' => 'required',
            'kategori_id' => 'required',
            'merk' => 'required',
            'harga_beli' => 'required',
            'margin_keuntungan' => 'required',
        ]);

        $barang = Products::where('id', $request->product_id)->first();
        $barang->kategori_id = $request->kategori_id;
        $barang->nama_barang = $request->name;
        $barang->merk = $request->merk;
        $barang->harga_beli = $request->harga_beli;
        $barang->harga_jual = $request->harga_jual;
        $barang->margin_keuntungan = $request->margin_keuntungan;
        $barang->satuan_barang = $request->satuan_barang;
        $barang->stok = $request->stok;
        $barang->save();

        return redirect()->route('products.edit', ['id' => $request->product_id])->with('message', 'Berhasil Mengupdate Barang');
    }

    public function cekKehadiran(Request $request)
    {

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

    public function rekamKehadiran(Request $request)
    {

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

    public function destroy($id)
    {
        Products::find($id)->delete();

        return redirect()->route('products.index')->with('message', 'Berhasil Delete Barang');
    }

    public function generateKodeBarang($lastCode)
    {
        $lastNumber = (int) substr($lastCode, 2);

        // Menambahkan 1 ke angka terakhir
        $nextNumber = $lastNumber + 1;

        // Membuat kode dengan format "BR" dan menggunakan sprintf untuk menambahkan angka dengan format 3 digit (misal: BR001)
        $nextCode = sprintf("BR%03d", $nextNumber);

        return $nextCode;
    }
}
