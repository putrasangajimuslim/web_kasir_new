@extends('layouts.app')

@section('title')
    {{ __('Halaman Transasaksi') }} | {{ config('app.name') }}
@endsection

@section('style')
    <style>
        .large-header {
            white-space: nowrap; /* Prevent text from wrapping */
            padding: 10px 20px;  /* Adjust padding as needed */
            font-size: 16px;     /* Adjust font size as needed */
        }

        .hidden {
            display: none;
        }

        .input-qty {
            width: 500px;
        }
    </style>
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
            <table class="table table-bordered" id="dtTransaksi" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Masa Expired</th>
                        <th>Action</th>
                    </tr>
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
                        <table class="table table-bordered" id="dtSearchBrg" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="large-header">Kode Barang</th>
                                    <th class="large-header">Nama Barang</th>
                                    <th class="large-header">Harga Jual</th>
                                    <th class="large-header">Stok</th>
                                    <th class="large-header">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="btnCheckoutSubmit" data-dismiss="modal" disabled>Checkout</button>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var selectedIds = [];
            $('#dtSearchBrg').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.search_products') }}",
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    // },
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
                        data: 'harga_jual',
                        name: 'harga_jual',
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
                ],
                drawCallback: function(settings) {
                    // Event handler untuk tombol #btnCheckout
                    $('#dtSearchBrg').off('click', '#btnCheckout').on('click', '#btnCheckout', function() {
                        var $this = $(this);
                        var $icon = $this.find('i');
                        var productId = $this.data('id'); // Ambil ID produk dari atribut data
                        // var inputNumber = '<input type="number" class="input-number" value="1">';
                        // $(this).parent().append(inputNumber);

                        var inputQty = document.querySelector('.hidden');
                        

                        if ($this.hasClass('btn-primary')) {
                            $this.removeClass('btn-primary').addClass('btn-danger');
                            $icon.removeClass('fa-plus').addClass('fa-minus');
                            selectedIds.push(productId); // Tambahkan ID ke array
                        } else {
                            $this.removeClass('btn-danger').addClass('btn-primary');
                            $icon.removeClass('fa-minus').addClass('fa-plus');
                            selectedIds = selectedIds.filter(function(id) {
                                return id !== productId; // Hapus ID dari array
                            });
                        }

                        // Cek apakah selectedIds kosong atau tidak untuk mengelola tombol Checkout
                        if (selectedIds.length > 0) {
                            $('#btnCheckoutSubmit').removeAttr('disabled');
                        } else {
                            $('#btnCheckoutSubmit').attr('disabled', 'disabled');
                        }
                    });
                }
            });

            let no = 1;
            $('#dtTransaksi').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaksi.index') }}",
                columns: [
                    {
                        data: null, // This is used to generate the row number
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'products.nama_barang',
                        name: 'products.nama_barang',
                        orderable: false,
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                        orderable: false,
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        orderable: false,
                    },
                    {
                        data: 'products.date_expired',
                        name: 'products.date_expired',
                        orderable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                    },
                ],
            });

            $('#btnCheckoutSubmit').on('click', function() {
                // Kirim data selectedIds dengan AJAX
                $.ajax({
                    url: '{{ route("transaksi.store") }}',  // Ganti dengan endpoint Anda
                    method: 'POST',
                    data: {
                        selectedIds: selectedIds,
                        _token: '{{ csrf_token() }}' // Jika menggunakan Laravel, sertakan token CSRF
                    },
                    success: function(response) {
                        // Handle sukses
                        alert('Checkout berhasil');
                        // Reset selectedIds dan tombol Checkout
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        alert('Checkout gagal');
                    }
                });
            });

            // $(document).on('change', '.jumlah_brg', function() {
            //     var value = $(this).val();
            //     var id = $(this).data('id');

            //     if (value < 0) {
            //         $(this).val(0);
            //         value = 0;
            //     }

            //     // $.ajax({
            //     //     url: '{{ route("transaksi.update") }}',
            //     //     type: 'POST',
            //     //     data: {
            //     //         id: id,
            //     //         value: value,
            //     //         _token: '{{ csrf_token() }}'
            //     //     },
            //     //     success: function(response) {
            //     //         // alert(response.message)
            //     //     },
            //     //     error: function(error) {
            //     //         console.error('Error occurred:', error);
            //     //     }
            //     // });
            // });
        });
    </script>
@endsection