<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isAdminAccess = false;

        $products = [];

        if ($user->role == 'admin') {
            $products = Transaksi::get();
            $products = Products::get();
            $isAdminAccess = true;
        }

        return view('admin.transaksi.index', ['isAdminAccess' => $isAdminAccess, 'products' => $products]);
    }

    public function create()
    {
        $categories = Transaksi::all();

        return view('admin.barang.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'merk' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required',
            'masa_exp' => 'required',
        ]);

        $cekBarang = Transaksi::where('nama_barang', $request->name)
            ->where('merk', $request->merk)
            ->first();

        $latestKodeBarang = Transaksi::latest()->first();

        $kodeBarang = empty($latestKodeBarang) ? 'BR01' : $this->generateKodeBarang($latestKodeBarang->kode_barang);

        if (empty($cekBarang)) {
            $barang = new Transaksi();
            $barang->kode_barang = $kodeBarang;
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

            return redirect()->route('products.index')->with('message', 'Berhasil Menyimpan Barang');
        } else {

            return redirect()->back()->with('error', 'Maaf Untuk Data Barang Tersebut Sudah Ada');
        }
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
