<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Products;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class LaporanController extends Controller
{
    public function index(Request $request) {
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = Carbon::create()->month($month)->format('F');
        }
    
        // Menghasilkan daftar tahun, misalnya 20 tahun terakhir
        $years = range(Carbon::now()->year - 20, Carbon::now()->year);
    
        $query = Transaksi::where('status_pembayaran', 'Done');

        if ($request->has('todayFilter') && $request->todayFilter) {
            $today = Carbon::parse($request->todayFilter)->toDateString();
            $query->whereDate('tgl_transaksi', $today);
        } elseif ($request->has('bulan') && $request->bulan && $request->has('tahun') && $request->tahun) {
            $query->whereMonth('tgl_transaksi', $request->bulan)
                    ->whereYear('tgl_transaksi', $request->tahun);
        } else {
            $today = Carbon::now()->toDateString();
            $query->whereDate('tgl_transaksi', $today);
        }

        $transaksi = $query->get();

        if ($request->ajax()) {
            // Jika tidak ada transaksi yang cocok, kembalikan JSON kosong
            $detailTransaksi = [];
    
            if (!empty($transaksi)) {
                $detailTransaksi = DetailTransaksi::whereIn('transaksi_id', $transaksi->pluck('id'))
                                    ->with(['products', 'transaksi.kasir'])
                                    ->orderBy('detail_transaksi.id', 'asc');
            }
    
            return DataTables::of($detailTransaksi)
                ->addIndexColumn()
                ->toJson();
        }
    
        return view('admin.laporan.index', [
            'months' => $months,
            'years' => $years,
        ]);
    }    
}
