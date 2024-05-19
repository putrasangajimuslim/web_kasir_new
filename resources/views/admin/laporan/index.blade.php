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
            <button class="btn btn-primary">Excel</button>
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
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Masa Expired</th>
                        <th>Kasir</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
            }
        },
        columns: [
            { data: 'products.nama_barang', name: 'products.nama_barang' },
            { data: 'products.harga_beli', name: 'products.harga_beli' },
            { data: 'harga_jual', name: 'harga_jual' },
            { data: 'products.stok', name: 'products.stok' },
            { data: 'products.date_expired', name: 'products.date_expired' },
            { data: 'transaksi.kasir.nama', name: 'transaksi.kasir.nama' },
        ],
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
    });
</script>
@endsection
