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
            <button class="btn btn-success mx-2">Refresh</button>
            <button class="btn btn-primary">Excel</button>
        </div>

        <span class="mb-4">Filter Bulan</span>
        <div class="container my-4">
            
            <div class="row">
              <div class="col-sm">
                <select name="" id="" class="form-control">
                    <option value="">-- Pilih Bulan --</option>
                </select>
              </div>
              <div class="col-sm">
                <select name="" id="" class="form-control">
                    <option value="">-- Pilih Tahun --</option>
                </select>
              </div>
              <div class="col-sm">
                  <button class="btn btn-primary">Search</button>
              </div>
            </div>
        </div>

        <span class="mb-4">Filter Hari</span>
        <div class="container my-4">
            
            <div class="row">
              <div class="col-sm">
                <input type="date" class="form-control">
              </div>
              <div class="col-sm">
                <button class="btn btn-primary">Search</button>
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
                        <th>Status Layak</th>
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