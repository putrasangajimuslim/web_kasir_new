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
use DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $role = $user->role;

        if ($request->ajax()) {
            $data = Products::orderBy('id', 'desc');

            if ($role == 'admin') {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status_exp', function ($row) {
                        $expiration = Carbon::parse($row->date_expired);
                        $statusExp = $expiration->isPast() ? '<p class="text-danger">expired</p>' : '<p class="text-secondary">belum expired</p>';
                        return $statusExp;
                    })
                    ->addColumn('action', function ($row) {
                        $edit = '<a href="' . route('products.edit', $row->id) . '" class="btn btn-primary btn-rounded btn-icon-md mr-2" title="Edit"><i class="fas fa-fw fa-edit"></i></a>';
                        $delete = '<a href="#" data-href="' . route('products.destroy', $row->id) . '" class="btn btn-danger btn-rounded btn-icon-md" title="Delete" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->id . '"><i class="fas fa-fw fa-trash"></i></a>';
                        
                        if (Carbon::parse($row->date_expired)->isPast()) {
                            return '';
                        }

                        return $edit . $delete;
                    })
                    ->rawColumns(['status_exp', 'action'])
                    ->toJson();
            } else {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status_exp', function ($row) {
                        $expiration = Carbon::parse($row->date_expired);
                        $statusExp = $expiration->isPast() ? '<p class="text-danger">expired</p>' : '<p class="text-secondary">belum expired</p>';
                        return $statusExp;
                    })
                    ->addColumn('action', function ($row) {
                        return '';
                    })
                    ->rawColumns(['status_exp', 'action'])
                    ->toJson();
            }
        }

        return view('admin.barang.index', ['role' => $role]);
    }

    public function searchProducts(Request $request)
    {
        $user = Auth::user();

        $role = $user->role;

        if ($request->ajax()) {
            $today = Carbon::now();

            // Mengambil data produk yang belum expired
            $data = Products::where('date_expired', '>=', $today)
                ->orderBy('id', 'desc')
                ->get(); // Fetch data from the database

            if ($role == 'admin') {
                return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '';
                })
                ->rawColumns(['action'])
                ->toJson();
            } else {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $action = '<div class="action_dt">
                        <button class="btn btn-primary btn-rounded btn-icon-md" id="btnCheckout" data-id="' . $row->id . '" title="Edit"><i class="fas fa-fw fa-plus"></i></button>
                        </div>';
                        return $action;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            }
        }

        return view('admin.barang.index');
    }

    public function create()
    {
        return view('admin.barang.create');
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

        $cekBarang = Products::where('nama_barang', $request->name)
            ->where('merk', $request->merk)
            ->first();

        $latestKodeBarang = Products::latest()->first();

        $kodeBarang = empty($latestKodeBarang) ? 'BR01' : $this->generateKodeBarang($latestKodeBarang->kode_barang);

        if (empty($cekBarang)) {
            $barang = new Products();
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
        $barang = Products::where('id', $id)->first();

        return view('admin.barang.edit', ['barang' => $barang]);
    }

    public function update(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'merk' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required',
            'masa_exp' => 'required',
        ]);

        $barang = Products::where('id', $request->product_id)->first();
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
        Products::find($id)->delete();

        return redirect()->route('products.index')->with('message', 'Berhasil Hapus Barang');
    }

    public function generateKodeBarang($lastCode)
    {
        $lastNumber = (int) substr($lastCode, 2);

        // Menambahkan 1 ke angka terakhir
        $nextNumber = $lastNumber + 1;

        // Membuat kode dengan format "BR" dan menggunakan sprintf untuk menambahkan angka dengan format 3 digit (misal: BR001)
        $nextCode = sprintf("BR%02d", $nextNumber);

        return $nextCode;
    }
}
