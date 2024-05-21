@extends('layouts.app')

@section('title')
    {{ __('Halaman Laporan') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan</h6>
    </div>
    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex mb-4">
            <button class="btn btn-success mx-2" onclick="location.reload()">Refresh</button>
            <button class="btn btn-primary" id="btnExport">Excel</button>
        </div>

        <span class="mb-4">Filter Bulan</span>
        <div class="container my-4">
            <div class="row">
                <div class="col-sm">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm">
                    <button class="btn btn-primary" onclick="filterByMonth()">Search</button>
                </div>
            </div>
        </div>

        <span class="mb-4">Filter Hari</span>
        <div class="container my-4">
            <div class="row">
                <div class="col-sm">
                    <input type="date" id="todayFilter" name="todayFilter" class="form-control">
                </div>
                <div class="col-sm">
                    <button class="btn btn-primary" onclick="filterByDate()">Search</button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dtLaporans" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Masa Expired</th>
                        <th>Kasir</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">Total</td>
                        <td style="background-color: #17A975; color: #fff;">Keuntungan</td>
                        <td style="background-color: #17A975; color: #fff;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form id="exportLaporan" action="laporan/export-laporan" method="POST" target="_blank">
            @csrf
            <input type="hidden" id="total_keuntungan" name="total_keuntungan">
            <input type="hidden" id="data_laporans" name="data_laporans">
        </form>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#dtLaporans').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan.index') }}",
                data: function(d) {
                    d.todayFilter = $('#todayFilter').val();
                    d.bulan = $('#bulan').val();
                    d.tahun = $('#tahun').val();
                },
                dataSrc: function(json) {
                    // Update footer with totals
                    // $('#dtLaporans tfoot td').eq(1).text(json.totalStok);
                    // $('#dtLaporans tfoot td').eq(2).text(json.totalQty);
                    // $('#dtLaporans tfoot td').eq(3).text(json.totalHargaBeli);
                    // $('#dtLaporans tfoot td').eq(4).text(json.totalHargaJual);
                    $('#dtLaporans tfoot td').eq(2).text(json.totalKeuntungan);
                    return json.data;
                }
            },
            columns: [
                { data: 'transaksi.tgl_transaksi', name: 'transaksi.tgl_transaksi' },
                { data: 'products.nama_barang', name: 'products.nama_barang' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'products.harga_beli', name: 'products.harga_beli' },
                { data: 'harga_jual', name: 'harga_jual' },
                { data: 'products.date_expired', name: 'products.date_expired' },
                { data: 'transaksi.kasir.nama', name: 'transaksi.kasir.nama' },
            ]
        });

        window.filterByDate = function() {
            $('#bulan').val('');
            $('#tahun').val('');
            table.ajax.reload();
        }

        window.filterByMonth = function() {
            $('#todayFilter').val('');
            table.ajax.reload();
        }

        $('#btnExport').on('click', function() {
            var originalData = table.rows().data().toArray();
            var filteredData = getFilteredData();
            let dataEmpty = '';

            var newLaporans = [];

            if (filteredData.length === 0) {
                dataEmpty = 'Data Tidak Ditemukan';
            }

            accumulationProfirt = 0;
            filteredData.forEach(element => {
                accumulationProfirt += element.keuntungan;
                newLaporans.push({
                    tgl_transaksi: element.transaksi.tgl_transaksi,
                    transaksi_id: element.transaksi_id,
                    barang_id: element.barang_id,
                    nama_barang: element.products.nama_barang,
                    kode_barang: element.products.kode_barang,
                    nama_barang: element.products.nama_barang,
                    stok: element.products.stok,
                    harga_beli: element.products.harga_beli,
                    harga_jual: element.products.harga_jual,
                    merk: element.products.merk,
                    masa_exp: element.products.date_expired,
                    jumlah_transaksi: element.jumlah,
                    subtotal_item: element.subtotal_item,
                    total_kembalian: element.transaksi.total_kembalian,
                    total_pembayaran: element.transaksi.total_pembayaran,
                    kasir_id: element.transaksi.kasir_id,
                    nama_kasir: element.transaksi.kasir.nama,
                });
            });

            $("#total_keuntungan").val(accumulationProfirt);
            $("#data_laporans").val(JSON.stringify(newLaporans));

            $("#exportLaporan").submit();
        });

        function getFilteredData() {
            return table.rows({ search: 'applied' }).data().toArray();
        }
    });
</script>
@endsection
