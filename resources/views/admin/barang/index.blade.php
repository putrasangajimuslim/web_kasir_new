@extends('layouts.app')

@section('title')
    {{ __('Halaman Barang') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Barang</h6>
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


        @if ($isAdminAccess)
            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-fw mb-4">Tambah Barang</a>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dtBarangs" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Masa Expired</th>
                        <th>Status Layak</th>
                        <th>Stok</th>
                        @if ($isAdminAccess)
                            <th>Action</th>
                        @endif
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
        $(function() {
                var table = $('#dtBarangs').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('products.index') }}",
                    columns: [
                        {
                            data: 'id',
                            name: 'id',
                        },
                        {
                            data: 'kode_barang',
                            name: 'kode_barang',
                            orderable: false,
                        },
                        {
                            data: 'nama_barang',
                            name: 'nama_barang',
                            orderable: false,
                        },
                        {
                            data: 'merk',
                            name: 'merk',
                            orderable: false,
                        },
                        {
                            data: 'harga_beli',
                            name: 'harga_beli',
                            orderable: false,
                        },
                        {
                            data: 'harga_jual',
                            name: 'harga_jual',
                            orderable: false,
                        },
                        {
                            data: 'date_expired',
                            name: 'date_expired',
                            orderable: false,
                        },
                        {
                            data: 'status_exp',
                            name: 'status_exp',
                            orderable: false,
                        },
                        {
                            data: 'stok',
                            name: 'stok',
                            orderable: false,
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            });
    </script>
@endsection