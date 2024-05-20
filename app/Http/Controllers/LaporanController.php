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
                                    ->with(['products' => function($query) {
                                        $query->withTrashed();
                                    }, 'transaksi.kasir'])
                                    ->orderBy('detail_transaksi.id', 'asc')
                                    ->get();

                // $totalStok= $detailTransaksi->sum(function ($detail) {
                //     return $detail->products ? $detail->products->stok : 0;
                // });

                // $totalQty= $detailTransaksi->sum(function ($detail) {
                //     return $detail->jumlah ? $detail->jumlah : 0;
                // });
        
                // $totalHargaBeli = $detailTransaksi->sum(function ($detail) {
                //     return $detail->products ? $detail->products->harga_beli : 0;
                // });
        
                // $totalHargaJual = $detailTransaksi->sum('harga_jual');
        
                // $totalKeuntungan = $totalHargaJual - $totalHargaBeli;

                $totalKeuntungan = $detailTransaksi->sum('keuntungan');
            }
    
            return DataTables::of($detailTransaksi)
                ->addColumn('tgl_transaksi', function ($detail) {
                    return Carbon::parse($detail->transaksi->tanggal)->format('Y-m-d');
                })
                ->addIndexColumn()
                // ->with('totalStok', $totalStok)
                // ->with('totalQty', $totalQty)
                // ->with('totalHargaBeli', $totalHargaBeli)
                // ->with('totalHargaJual', $totalHargaJual)
                ->with('totalKeuntungan', $totalKeuntungan)
                ->toJson();
        }
    
        return view('admin.laporan.index', [
            'months' => $months,
            'years' => $years,
        ]);
    }    
}
