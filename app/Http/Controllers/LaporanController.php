<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExportExcel;
use App\Models\DetailTransaksi;
use App\Models\Products;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;

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

                $totalAllStok = $detailTransaksi->sum(function ($detail) {
                    return $detail->products ? $detail->products->stok : 0;
                });

                $totalQty= $detailTransaksi->sum(function ($detail) {
                    return $detail->jumlah ? $detail->jumlah : 0;
                });
        
                $totalHargaBeli = $detailTransaksi->sum(function ($detail) {
                    $qty = $detail->jumlah;
                    $total = $detail->products->harga_beli * $qty;
                    return $total;
                    // return $detail->products ? $detail->products->harga_beli : 0;
                });
        
                // $totalHargaJual = $detailTransaksi->sum('harga_jual');

                $totalHargaJual = $detailTransaksi->sum(function ($detail) {
                    $qty = $detail->jumlah;
                    $total = $detail->harga_jual * $qty;
                    return $total;
                    // return $detail->products ? $detail->products->harga_beli : 0;
                });
        
                $totalKeuntungan = $totalHargaJual - $totalHargaBeli;

                $today = Carbon::today();

                $expiredProducts = $detailTransaksi->filter(function ($detail) {
                    return $detail->products && Carbon::parse($detail->products->date_expired)->isPast();
                });
        
                $totalExpiredValue = $expiredProducts->sum(function ($detail) {
                    return $detail->products->harga_beli * $detail->products->stok;
                });

                // $totalKeuntungan = $detailTransaksi->sum('keuntungan');
            }
    
            return DataTables::of($detailTransaksi)
                ->addColumn('tgl_transaksi', function ($detail) {
                    return Carbon::parse($detail->transaksi->tanggal)->format('Y-m-d');
                })
                ->addIndexColumn()
                // ->with('totalStok', $totalStok)
                ->with('totalQty', $totalQty)
                ->with('totalAllStok', $totalAllStok)
                ->with('totalHargaBeli', $totalHargaBeli)
                ->with('totalHargaJual', $totalHargaJual)
                ->with('totalKeuntungan', $totalKeuntungan)
                ->with('totalKerugian', $totalExpiredValue)
                ->toJson();
        }
    
        return view('admin.laporan.index', [
            'months' => $months,
            'years' => $years,
        ]);
    }    

    public function exportExcel(Request $request) {
        $tgl_transaksi = $request->tgl_transaksi;
        $totalKeuntungan = $request->total_keuntungan;
        $data_laporans = json_decode($request->data_laporans);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header di baris pertama
        $sheet->setCellValue('A1', 'Filter By: ');
        $sheet->setCellValue('B1', $tgl_transaksi);

        $labels = ['Tanggal Transaksi', 'Nama Barang', 'Qty', 'Harga Beli', 'Harga Jual', 'Stok Barang', 'Masa Expired', 'Kasir'];
        $startColumn = 'A';
        foreach ($labels as $index => $label) {
            $cell = $startColumn . '3';
            $sheet->setCellValue($cell, $label);
        
            // Menambahkan styling untuk header
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1E90FF'); // Warna hijau
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFFFFF'); // Warna putih
            $startColumn++;
        }

        // Mendapatkan indeks baris awal dari data
        $startRow = 4;

        $totalQty = 0;
        $totalHargaBeli = 0;
        $totalHargaJual = 0;
        $totalStok = 0;
        $totalExpiredValue = 0;
        // Memasukkan data dari $data_laporans
        foreach ($data_laporans as $index => $data_laporan) {
            // Reset startColumn ke A
            $totalQty += $data_laporan->jumlah_transaksi;
            $totalHargaBeli += $data_laporan->harga_beli * $data_laporan->jumlah_transaksi;
            $totalHargaJual += $data_laporan->harga_jual * $data_laporan->jumlah_transaksi;
            $totalStok += $data_laporan->stok;

            if (Carbon::parse($data_laporan->masa_exp)->isPast()) {
                $totalExpiredValue += $data_laporan->harga_beli * $data_laporan->stok;
            }

            $startColumn = 'A';

            // Menentukan indeks baris untuk setiap data
            $currentRow = $startRow + $index;

            // Memasukkan data ke setiap kolom
            $sheet->setCellValue('A' . $currentRow, $data_laporan->tgl_transaksi);
            $sheet->setCellValue('B' . $currentRow, $data_laporan->nama_barang);
            $sheet->setCellValue('C' . $currentRow, $data_laporan->jumlah_transaksi);
            $sheet->setCellValue('D' . $currentRow, $data_laporan->harga_beli);
            $sheet->setCellValue('E' . $currentRow, $data_laporan->harga_jual);
            $sheet->setCellValue('F' . $currentRow, $data_laporan->stok);
            $sheet->setCellValue('G' . $currentRow, $data_laporan->masa_exp);
            $sheet->setCellValue('H' . $currentRow, $data_laporan->nama_kasir);

            // Menambahkan border tipis untuk setiap sel data
            for ($i = 0; $i < count($labels); $i++) {
                $cell = chr(ord('A') + $i) . $currentRow;
                $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        }

        $totalRow = $startRow + count($data_laporans);
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);

        $totalQty = array_sum(array_column($data_laporans, 'jumlah_transaksi'));

        $sheet->setCellValue('C' . $totalRow, $totalQty);
        $sheet->setCellValue('D' . $totalRow, $totalHargaBeli);
        $sheet->setCellValue('E' . $totalRow, $totalHargaJual);
        $sheet->setCellValue('F' . $totalRow, $totalStok);

        // Menambahkan styling untuk kolom total
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF'); // Warna putih
        $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);

        $sheet->setCellValue('G' . $totalRow, $totalKeuntungan);
        $sheet->setCellValue('H' . $totalRow, $totalExpiredValue);

        // Menambahkan styling untuk kolom keuntungan
        $sheet->getStyle('G' . $totalRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('17A975'); // Warna hijau muda
        $sheet->getStyle('H' . $totalRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F61C1C'); // Warna hijau muda
        $sheet->getStyle('G' . $totalRow . ':H' . $totalRow)->getFont()->setBold(true);

        // Menambahkan border tipis untuk total dan keuntungan
        for ($i = 0; $i < count($labels); $i++) {
            $cell = chr(ord('A') + $i) . $totalRow;
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-penjualan.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        // Simpan file di storage
        $writer->save($filePath);

        // Kembalikan respon download
        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }
}
