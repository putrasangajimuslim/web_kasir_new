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
use Illuminate\Support\Facades\DB;
use DataTables;

class LaporanController extends Controller
{
    public function index(Request $request) {
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = Carbon::create()->month($month)->format('F');
        }
    
        // Generate a list of years, e.g., the last 20 years
        $years = range(Carbon::now()->year - 20, Carbon::now()->year);
    
        // Initialize the query for transactions
        $query = Transaksi::where('status_pembayaran', 'Done');
    
        // Apply filters based on request parameters
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
        $detailTransaksi = collect();
    
        if ($transaksi->isNotEmpty()) {
            $detailTransaksi = DetailTransaksi::whereIn('transaksi_id', $transaksi->pluck('id'))
                ->with(['products' => function($query) {
                    $query->withTrashed();
                }, 'transaksi.kasir'])
                ->orderBy('detail_transaksi.id', 'asc')
                ->get();
        }
    
        // Group the data by barang_id and calculate the totals
        $groupedData = $detailTransaksi->groupBy('barang_id')->map(function ($items, $barang_id) {
            $firstItem = $items->first();
            $product = $firstItem->products;
            $transaksi = $firstItem->transaksi;
            $totalJumlah = $items->sum('jumlah');
    
            return [
                'tgl_transaksi' => $transaksi->tgl_transaksi,
                'name_kasir' => $transaksi->kasir->nama,
                'barang_id' => $barang_id,
                'nama_brg' => $product->nama_barang,
                'harga_beli' => $product->harga_beli,
                'harga_jual' => $product->harga_jual,
                'total_jumlah' => $totalJumlah,
                'total_stok' => $product->stok,
                'masa_exp' => $product->date_expired,
            ];
        })->values();
    
        $totalHargaBeli = $groupedData->sum(fn($detail) => $detail['harga_beli'] * $detail['total_jumlah']);
        $totalHargaJual = $groupedData->sum(fn($detail) => $detail['harga_jual'] * $detail['total_jumlah']);
        $totalQty = $groupedData->sum('total_jumlah');
        $totalAllStok = $groupedData->sum('total_stok');
        $totalKeuntungan = $totalHargaJual - $totalHargaBeli;
    
        // Calculate the total value of expired products
        $expiredProducts = $groupedData->filter(fn($detail) => Carbon::parse($detail['masa_exp'])->isPast());
        $totalExpiredValue = $expiredProducts->sum(fn($detail) => $detail['harga_beli'] * $detail['total_stok']);
    
        if ($request->ajax()) {
            return DataTables::of($groupedData)
                ->addIndexColumn()
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
