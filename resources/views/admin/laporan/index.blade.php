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
                        <th>Stok Barang</th>
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
                        <td colspan="2">Total</td>
                        <td>x 1</td>
                        <td>x 2</td>
                        <td>x 3</td>
                        <td>x 4</td>
                        <td style="background-color: #17A975; color: #fff;"></td>
                        <td style="background-color: #f61d1d; color: #fff;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form id="exportLaporan" action="laporan/export-laporan" method="POST" target="_blank">
            @csrf
            <input type="hidden" id="tgl_transaksi" name="tgl_transaksi">
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
                    $('#dtLaporans tfoot td').eq(1).text(json.totalAllStok);
                    $('#dtLaporans tfoot td').eq(2).text(json.totalQty);
                    $('#dtLaporans tfoot td').eq(3).text(json.totalHargaBeli);
                    $('#dtLaporans tfoot td').eq(4).text(json.totalHargaJual);
                    $('#dtLaporans tfoot td').eq(5).text(json.totalKeuntungan);
                    $('#dtLaporans tfoot td').eq(6).text(json.totalKerugian);
                    return json.data;
                }
            },
            columns: [
                { data: 'tgl_transaksi', name: 'tgl_transaksi' },
                { data: 'nama_brg', name: 'nama_brg' },
                { data: 'total_stok', name: 'total_stok' },
                { data: 'total_jumlah', name: 'total_jumlah' },
                { data: 'harga_beli', name: 'harga_beli' },
                { data: 'harga_jual', name: 'harga_jual' },
                { data: 'masa_exp', name: 'masa_exp' },
                { data: 'name_kasir', name: 'name_kasir' },
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
            let date = '';
            let monthFilter = $('#bulan').val();
            let yearFilter = $('#tahun').val();
            let todayFilter = $('#todayFilter').val();

            if (todayFilter) {
                date = todayFilter;
            } else if (monthFilter && yearFilter) {
                date = `${monthFilter} ${yearFilter}`;
            }
            
            $('#tgl_transaksi').val(date);

            var originalData = table.rows().data().toArray();
            var filteredData = getFilteredData();
            let dataEmpty = '';

            var newLaporans = [];

            if (filteredData.length === 0) {
                dataEmpty = 'Data Tidak Ditemukan';
            }

            totalHargaBeli = 0;
            totalHargaJual = 0;
            filteredData.forEach(element => {
                // accumulationProfirt += element.keuntungan;
                totalHargaBeli += element.harga_beli * element.total_jumlah;
                totalHargaJual += element.harga_jual * element.total_jumlah;

                newLaporans.push({
                    tgl_transaksi: element.tgl_transaksi,
                    barang_id: element.barang_id,
                    harga_beli: element.harga_beli,
                    harga_jual: element.harga_jual,
                    masa_exp: element.masa_exp,
                    nama_barang: element.nama_brg,
                    nama_kasir: element.name_kasir,
                    jumlah_transaksi: element.total_jumlah,
                    stok: element.total_stok,
                });
            });

            accumulationProfit = totalHargaJual - totalHargaBeli;

            $("#total_keuntungan").val(accumulationProfit);
            $("#data_laporans").val(JSON.stringify(newLaporans));

            $("#exportLaporan").submit();
        });

        function getFilteredData() {
            return table.rows({ search: 'applied' }).data().toArray();
        }
    });
</script>
@endsection
