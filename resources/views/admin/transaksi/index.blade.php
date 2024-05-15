@extends('layouts.app')

@section('title')
    {{ __('Halaman Kehadiran') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kasir</h6>
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

       <button type="button" class="card shadow shadow-none text-center col-2 mb-4" data-toggle="modal" data-target="#searchProduct">
            <div class="p-2">
                <span class="mr-2">Cari Barang</span>
                <i class="fas fa-fw fa-search"></i>
            </div>
       </button>

        <div class="d-flex mt-5">
            <span class="mr-2">Tanggal</span>
            @php
                $tgl = date('Y-m-d');
            @endphp
            <div class="card shadow shadow-none col-2 mb-4">
                <span>{{ $tgl }}</span>
            </div>
        </div>

        <button class="btn btn-danger mb-3">Reset Keranjang</button>
        
        <div class="table-responsive">
            <table id="dtkasir" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col">
            <div class="form-group">
                <label for="inputEmail3">Total</label>
                <input type="email" class="form-control" id="inputEmail3">
            </div>
            </div>
            <div class="col">
            <div class="form-group">
                <label for="inputEmail3">Bayar</label>
                <input type="email" class="form-control" id="inputEmail3">
            </div>
            </div>
            <div class="col">
            <div class="form-group">
                <label for="inputEmail3">Kembali</label>
                <input type="email" class="form-control" id="inputEmail3">
            </div>
            </div>
        </div>

        <div class="d-flex">
            <button class="btn btn-success mr-2">Bayar</button>
            <button class="btn btn-primary">Print</button>
        </div>

        <div class="modal fade" id="searchProduct" tabindex="-1" role="dialog" aria-labelledby="searchProduct" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="searchProduct">Cari Barang</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        {{-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->kode_barang }}</td>
                                    <td>{{ $product->nama_barang }}</td>
                                    <td>{{ $product->harga_jual }}</td>
                                    <td>{{ $product->stok }}</td>
                                    <td>
                                        <button class="btn btn-success">+</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table> --}}
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal" disabled>Checkout</button>
                </div>
              </div>
            </div>
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