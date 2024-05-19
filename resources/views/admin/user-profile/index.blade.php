@extends('layouts.app')

@section('title')
    {{ __('Halaman Laporan') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
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

        
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#dtLaporans').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    // { data: 'kode_barang', name: 'kode_barang', sortable: false },
                    // { data: 'nama_barang', name: 'nama_barang', sortable: false },
                    // { data: 'merk', name: 'merk', sortable: false },
                    // { data: 'harga_beli', name: 'harga_beli', sortable: false },
                    // { data: 'harga_jual', name: 'harga_jual', sortable: false },
                    // { data: 'date_expired', name: 'date_expired', sortable: false },
                    // { data: 'status_exp', name: 'status_exp', sortable: false },
                    // { data: 'stok', name: 'stok', sortable: false },
                    // { data: 'action', name: 'action', sortable: false },
                ],
                initComplete: function () {
                    $('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc').addClass('no-sort');
                }
            });
        });
    </script>
@endsection