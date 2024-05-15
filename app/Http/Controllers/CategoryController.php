<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isAdminAccess = false;

        $categories = [];

        if ($user->role == 'admin') {
            $categories = Categories::get();
            $isAdminAccess = true;
        }

        return view('admin.kategori.index', ['isAdminAccess' => $isAdminAccess, 'categories' => $categories]);
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        $cek = Categories::where('nama', $request->name)->first();

        if (empty($cek)) {
            $category = new Categories();
            $category->nama = $request->name;
            $category->save();

            return redirect()->route('kategori.index')->with('message', 'Berhasil Menyimpan Kategori');
        } else {

            return redirect()->back()->with('error', 'Maaf Untuk Data Kategori Tersebut Sudah Ada');
        }
    }

    // public function detailSlipGaji()
    // {
    //     $user = Auth::user();

    //     $isAdminAccess = false;


    //     if ($user->role == 'admin') {
    //         $isAdminAccess = true;
    //         $slipgajis = Kelola::all();
    //     } else {
    //         $slipgajis = Kelola::where('kode_karyawan', $user->kode_karyawan)->get();
    //     }

    //     return view('admin.laporan.detail_slipgaji', ['isAdminAccess' => $isAdminAccess, 'slipgajis' => $slipgajis]);
    // }

    // public function detailRekapGaji()
    // {
    //     $periodes = Kelola::select('bulan', 'tahun')
    //         ->groupBy('bulan', 'tahun')
    //         ->get();

    //     return view('admin.laporan.detail_rekapgaji', ['periodes' => $periodes]);
    // }

    // public function print($id)
    // {
    //     // return Excel::download(new LaporanSlipGaji, 'slipgaji.pdf', ExcelSupport::DOMPDF);
    //     $kelola = Kelola::where('id', $id)->first();

    //     $user = User::where('kode_karyawan', $kelola->kode_karyawan)->with('jabatan')->first();

    //     $kelola->nama_pegawai = $user->nama;
    //     $kelola->jabatan_pegawai = $user->jabatan->nama_jabatan;

    //     $formattedDate = Carbon::createFromFormat('m-Y', $kelola->bulan . '-20' . substr($kelola->bulan, -2))->formatLocalized('%B');
    //     $kelola->bln = $formattedDate;

    //     $pdf = PDF::loadView('admin.laporan.generate_laporan_slipgaji', [
    //         'kelola' => $kelola,
    //     ]);

    //     return $pdf->download('slipgaji.pdf');
    // }

    // public function detailPeriodeRekapGajiPeriode($bln, $thn)
    // {
    //     $dataKelola = Kelola::where('bulan', $bln)
    //         ->where('tahun', $thn)
    //         ->get();

    //     foreach ($dataKelola as $data) {
    //         $user = User::where('kode_karyawan', $data->kode_karyawan)->first();

    //         $data->nama_karyawan = $user->nama;
    //     }

    //     $formattedDate = Carbon::createFromFormat('m-Y', $bln . '-20' . substr($thn, -2))->formatLocalized('%B');
    //     $formatBln = $formattedDate;

    //     return view('admin.laporan.detail_perioderekapgaji', ['dataKelola' => $dataKelola, 'bln' => $bln, 'thn' => $thn, 'formatBln' => $formatBln]);
    // }

    // public function printPeriodeRekapGajiPeriode($bln, $thn)
    // {
    //     $dataKelola = Kelola::where('bulan', $bln)
    //         ->where('tahun', $thn)
    //         ->get();

    //     foreach ($dataKelola as $data) {
    //         $user = User::where('kode_karyawan', $data->kode_karyawan)->first();

    //         $data->nama_karyawan = $user->nama;
    //     }

    //     $formattedDate = Carbon::createFromFormat('m-Y', $bln . '-20' . substr($thn, -2))->formatLocalized('%B');
    //     $formatBln = $formattedDate;

    //     $pdf = PDF::loadView('admin.laporan.generate_laporan_rekapgajiperiode', [
    //         'dataKelola' => $dataKelola,
    //         'bln' => $bln,
    //         'thn' => $thn,
    //         'formatBln' => $formatBln
    //     ]);

    //     $nameFile = 'laporan rekapgaji periode ' . $formatBln . '/' . $thn . '.pdf';

    //     return $pdf->download($nameFile);
    // }
}
