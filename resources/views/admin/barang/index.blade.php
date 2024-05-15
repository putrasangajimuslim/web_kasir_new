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
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Masa Expired</th>
                        <th>Stok</th>
                        @if ($isAdminAccess)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>

                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $product->kode_barang }}</td>
                        <td>{{ $product->nama_barang }}</td>
                        <td>{{ $product->merk }}</td>
                        <td>{{ $product->harga_beli }}</td>
                        <td>{{ $product->harga_jual }}</td>
                        <td>{{ $product->date_expired }}</td>
                        <td>{{ $product->stok }}</td>
                        @if ($isAdminAccess)
                            <td>
                                <a href="{{ route('products.edit', ['id'=> $product->id]) }}"  class="btn btn-primary" id="btnEdit">Edit</a>
                                <button class="btn btn-danger btnDel" id="btnEdit" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('products.destroy', ['id' => $product->id]) }}"><i class="fas fa-trash"></i></button>

                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('admin.modal.destroy')
    <script>

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var url = button.data('url'); // Ambil nilai data-url dari tombol
            var form = $(this).find('form'); // Temukan elemen form di dalam modal
            form.attr('action', url);
        });
    </script>
@endsection