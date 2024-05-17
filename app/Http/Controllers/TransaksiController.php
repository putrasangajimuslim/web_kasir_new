<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Products;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $isAdminAccess = false;

        if ($user->role == 'admin') {
            if ($request->ajax()) {
                $today = Carbon::now()->toDateString(); 

                $transaksi = Transaksi::where('tgl_transaksi', $today)
                            ->where('kasir_id', $user->id)
                            ->first();

                $detailTransaksi = [];

                if (!empty($transaksi)) {
                    $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->with(['products'])
                                        ->orderBy('id', 'asc');
                }

                return DataTables::of($detailTransaksi)
                    ->addIndexColumn()
                    ->addColumn('jumlah', function ($row) {
                        $qty = '<input type="number" class="form-control jumlah_brg" value="' . $row->jumlah . '" data-id="' . $row->id . '" min="0" disabled>';
                        return $qty;
                    })
                    ->addColumn('action', function ($row) {
                        $action = '<button class="btn btn-danger btn-rounded btn-icon-md" id="btnRemove" data-id="' . $row->id . '">X</button>';
                        return $action;
                    })
                    ->rawColumns(['jumlah','action'])
                    ->toJson();
            }
            $isAdminAccess = true;
        }

        return view('admin.transaksi.index', ['isAdminAccess' => $isAdminAccess]);
    }

    public function create()
    {
        $categories = Transaksi::all();

        return view('admin.barang.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $selectedIds = $request->input('selectedIds', []);
        $today = Carbon::now()->toDateString(); // Ubah menjadi string tanggal saja
        $subtotal = 0;

        $transaksi = Transaksi::where('tgl_transaksi', $today)
            ->where('kasir_id', $user->id)
            ->first();

        foreach ($selectedIds as $productId) {
            // Ambil informasi produk berdasarkan ID
            $product = Products::find($productId);

            if (!$product) {
                // Handle jika produk tidak ditemukan
                continue; // Langsung lanjut ke produk berikutnya
            }

            // Jika transaksi belum ada, buat transaksi baru
            if (!$transaksi) {
                $transaksi = new Transaksi();
                $transaksi->tgl_transaksi = $today;
                $transaksi->kasir_id = $user->id;
                $transaksi->subtotal = 0; // Set default subtotal ke 0
                $transaksi->save();
            }

            // Check jika detail transaksi untuk produk sudah ada
            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)
                ->where('kode_barang', $product->kode_barang)
                ->first();

            if ($detailTransaksi) {
                // Jika detail transaksi untuk produk sudah ada, update jumlah dan subtotalnya
                $detailTransaksi->jumlah += 1;
                $detailTransaksi->subtotal_item = $detailTransaksi->jumlah * $product->harga_jual;
                $detailTransaksi->save();
            } else {
                // Jika detail transaksi untuk produk belum ada, buat detail transaksi baru
                $detailTransaksiBaru = new DetailTransaksi();
                $detailTransaksiBaru->transaksi_id = $transaksi->id;
                $detailTransaksiBaru->kode_barang = $product->kode_barang;
                $detailTransaksiBaru->jumlah = 1; // Jumlah default adalah 1
                $detailTransaksiBaru->harga_jual = $product->harga_jual;
                $detailTransaksiBaru->subtotal_item = $detailTransaksiBaru->jumlah * $product->harga_jual; // Subtotal item adalah harga jual awal
                $detailTransaksiBaru->save();
            }
        }

        $totalSubtotal = DetailTransaksi::where('transaksi_id', $transaksi->id)
            ->sum('subtotal_item');

        $transaksi->subtotal = $totalSubtotal;
        $transaksi->save();

        return response()->json(['message' => 'Checkout berhasil']);
    }

    public function edit($id)
    {
        $barang = Transaksi::where('id', $id)->first();

        return view('admin.barang.edit', ['barang' => $barang]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $qty = $request->value;

        if ($qty == 0) {
            DetailTransaksi::where('id', $id)->delete();
        } else {
            $detailTransaksi = DetailTransaksi::where('id', $id)->first();
            $detailTransaksi->jumlah = $qty;
            $detailTransaksi->subtotal_item = $qty * $detailTransaksi->harga_jual;
            $detailTransaksi->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Berhasil Melakukan Update Keranjang',
        ], 200);
    }

    public function destroy($id)
    {
        Transaksi::find($id)->delete();

        return redirect()->route('products.index')->with('message', 'Berhasil Delete Barang');
    }
}
