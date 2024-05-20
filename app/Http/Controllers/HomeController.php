<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\DetailTransaksi;
use App\Models\Products;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $userCount = User::count();
        $productsCount = Products::count();
        $transaksiCount = Transaksi::count();

        $cardData = [
            'totalUsers' => $userCount ?? 0,
            'totalProducts' => $productsCount ?? 0,
            'totalTransaksi' => $transaksiCount ?? 0,
        ];

        $currentYear = Carbon::now()->year;

        // $annualProfit = DetailTransaksi::whereYear('created_at', $currentYear)
        //                                 ->sum('keuntungan');

        $monthlyProfits = DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
                            ->where('transaksi.status_pembayaran', '=', 'Done')
                            ->whereYear('transaksi.tgl_transaksi', $currentYear)
                            ->groupBy(DB::raw('MONTH(transaksi.tgl_transaksi)'))
                            ->select(
                                DB::raw('MONTH(transaksi.tgl_transaksi) as month'),
                                DB::raw('SUM(detail_transaksi.keuntungan) as total_profit')
                            )
                            ->orderBy('month')
                            ->get()
                            ->pluck('total_profit', 'month')
                            ->toArray();

        $monthlyProfits = array_replace(array_fill(1, 12, 0), $monthlyProfits);
        // dd($monthlyProfits);

        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        $data = [65, 59, 80, 81, 56, 55, 40];

        return view('admin.dashboard', ['labels' => $labels, 'data' => $monthlyProfits, 'cardData' => $cardData]);
    }
}
