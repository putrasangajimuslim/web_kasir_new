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

        $role = $user->role;

        $total = 0;

        $today = Carbon::now()->toDateString(); 

        $transaksi = Transaksi::where('tgl_transaksi', $today)
                    ->where('kasir_id', $user->id)
                    ->where('status_pembayaran', '=', 'Pending')
                    ->first();

        $total = $transaksi->subtotal ?? '';

        if ($request->ajax()) {

            $detailTransaksi = [];

            if (!empty($transaksi)) {
                $total = $transaksi->subtotal;
                $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->with(['products'])
                                    ->orderBy('id', 'asc');
            }

            return DataTables::of($detailTransaksi)
                ->addIndexColumn()
                ->addColumn('jumlah', function ($row) {
                    $qty = '
                        <div class="container-item-kasir">
                            <button id="decrementBtn" data-id="'.$row->id.'" data-barang="'.$row->barang_id.'">-</button>
                            <input type=number class="title-qty" id="value_'.$row->id.'" value="'.$row->jumlah.'" readonly min="1" max="'.$row->products->stok.'">
                            <input type=number class="title-qty hidden" id="harga_'.$row->id.'" value="'.$row->harga_jual.'">
                            <input type=text class="title-qty hidden" id="detailid_'.$row->id.'" value="'.$row->id.'">
                            <button id="incrementBtn" data-id="'.$row->id.'" data-barang="'.$row->barang_id.'">+</button>
                        </div>
                    ';
                    return $qty;
                })
                ->addColumn('jumlah_int', function ($row) {
                    $qty_int = $row->jumlah;
                    return $qty_int;
                })
                ->addColumn('action', function ($row) {
                    $action = '<button class="btn btn-danger btn-rounded btn-icon-md" id="btnRemove" data-id="' . $row->id . '" data-barang="'.$row->barang_id.'">X</button>';
                    return $action;
                })
                ->rawColumns(['jumlah','action'])
                ->toJson();
        }

        return view('admin.transaksi.index', ['role' => $role, 'total' => $total, 'transaksi' => $transaksi]);
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
        $jumlah = 1;

        $transaksi = Transaksi::where('tgl_transaksi', $today)
            ->where('kasir_id', $user->id)
            ->first();

        foreach ($selectedIds as $productId) {
            $product = Products::find($productId);

            if (!$product) {
                continue; // Langsung lanjut ke produk berikutnya
            }

            if (!$transaksi) {
                $transaksi = new Transaksi();
                $transaksi->tgl_transaksi = $today;
                $transaksi->kasir_id = $user->id;
                $transaksi->subtotal = 0; // Set default subtotal ke 0
                $transaksi->status_pembayaran = "Pending"; // Set default subtotal ke 0
                $transaksi->total_pembayaran = 0; // Set default subtotal ke 0
                $transaksi->total_kembalian = 0; // Set default subtotal ke 0
                $transaksi->save();
            }

            $harga_jual = $product->harga_jual;
            $harga_beli = $product->harga_beli;

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)
                ->where('barang_id', $product->id)
                ->first();

            if ($detailTransaksi) {
                $detailTransaksi->jumlah = $jumlah + 1;
                $detailTransaksi->subtotal_item = $detailTransaksi->jumlah * $harga_jual;
                $detailTransaksi->keuntungan = ($harga_jual - $harga_beli) * $detailTransaksi->jumlah;
                $detailTransaksi->save();
            } else {
                $detailTransaksiBaru = new DetailTransaksi();
                $detailTransaksiBaru->transaksi_id = $transaksi->id;
                $detailTransaksiBaru->barang_id = $product->id;
                $detailTransaksiBaru->harga_jual = $harga_jual;
                $detailTransaksiBaru->jumlah = $jumlah;
                $detailTransaksiBaru->keuntungan = ($harga_jual - $harga_beli) * $jumlah;
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

    public function checkOutPayment(Request $request)
    {
        $transaksiId = $request->transaksi_id;
        $totalBrg = $request->total_brg;
        $bayarBrg = $request->bayar_brg;
        $kembaliBrg = $request->kembali_brg;
        $arrayDetailTransaksi = $request->array_detail_transaksi;

        $transaksi = Transaksi::find($transaksiId);
        if($transaksi) {
            $transaksi->status_pembayaran = 'Done';
            $transaksi->subtotal = $totalBrg;
            $transaksi->total_pembayaran = $bayarBrg;
            $transaksi->total_kembalian = $kembaliBrg;
            $transaksi->save();

            foreach ($arrayDetailTransaksi as $key => $value) {
                $detailTransaksiArray = DetailTransaksi::where('id', $value['detail_id'])->first();
                if ($detailTransaksiArray) {
                    $detailTransaksiArray->jumlah = $value['quantity'];
                    $detailTransaksiArray->subtotal_item = $value['subtotal'] * $value['quantity'];
                    $detailTransaksiArray->save();

                    $barang = Products::where('id', $detailTransaksiArray->barang_id)->first();
                    if ($barang) {
                        $barang->stok = $barang->stok - $value['quantity'];
                        $barang->save();
                    }
                }
            }
        }

        return response()->json(['error' => 'false', 'message' => 'Transaksi Pembayaran Berhasil']);
    }

    public function removeItem(Request $request) {
        $id = $request->id;
        $detailTransaksi = DetailTransaksi::where('id', $id)->delete();

        return response()->json(['error' => 'false', 'message' => 'Berhasil Menghapus Item']);
    }

    public function resetAllItem(Request $request) {
        $transaksiId = $request->transaksi_id;
        $transaksi = Transaksi::find($transaksiId)->delete();

        return response()->json(['error' => 'false', 'message' => 'Berhasil Menghapus Semua Item']);
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
