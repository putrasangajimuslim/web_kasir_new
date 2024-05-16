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

        $products = [];

        if ($user->role == 'admin') {
            if ($request->ajax()) {
                $data = Transaksi::where('kasir_id', $user->id)->orderBy('id', 'desc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $edit = '<a href="' . route('transaksi.edit', $row->id) . '" class="btn btn-secondary btn-rounded btn-icon-md" title="Edit"><i class="ti-pencil"></i></a>';
                        $delete = '<a href="#" data-href="' . route('transaksi.destroy', $row->id) . '" class="btn btn-danger btn-rounded btn-icon-md" title="Delete" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->id . '"><i class="ti-trash"></i></a>';
                        return $edit . $delete;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            }
            $isAdminAccess = true;
        }

        return view('admin.transaksi.index', ['isAdminAccess' => $isAdminAccess]);
    }

    public function getProducts()
    {
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
        $validateData = $request->validate([
            'name' => 'required',
            'merk' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'masa_exp' => 'required',
        ]);

        $barang = Transaksi::where('id', $request->product_id)->first();
        // $barang->kategori_id = $request->kategori_id;
        $barang->nama_barang = $request->name;
        $barang->merk = $request->merk;
        $barang->harga_beli = $request->harga_beli;
        $barang->harga_jual = $request->harga_jual;
        // $barang->margin_keuntungan = $request->margin_keuntungan;
        // $barang->satuan_barang = $request->satuan_barang;
        $barang->stok = $request->stok;
        $barang->date_expired = $request->masa_exp;
        $barang->save();

        return redirect()->route('products.edit', ['id' => $request->product_id])->with('message', 'Berhasil Mengupdate Barang');
    }

    public function destroy($id)
    {
        Transaksi::find($id)->delete();

        return redirect()->route('products.index')->with('message', 'Berhasil Delete Barang');
    }
}
