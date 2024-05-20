@extends('layouts.app')

@section('title')
    {{ __('Halaman Transaksi') }} | {{ config('app.name') }}
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

        .container-item-kasir {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-item-kasir button {
            /* padding: 10px 20px; */
            font-size: 24px;
            background-color: #fff;
            border: none;
            cursor: pointer;
        }

        .container-item-kasir button:hover {
            background-color: #f0f0f0;
        }

        .title-qty {
            width: 200px;
            font-size: 18px;
            margin-left: 24px;
            margin-right: 24px;
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

        <!-- <div class="container-item-kasir">
            <div class="card-item-kasir">
                <button id="decrementBtn" onclick="decrement()">-</button>
                <span id="value">1</span>
                <button id="incrementBtn" onclick="increment()">+</button>
            </div>
        </div> -->

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

        @if ($transaksi)
            <button class="btn btn-danger mb-3" id="btnResetItem">Reset Keranjang</button>
        @endif
        
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

        <input type=text class="title-qty hidden" id="transaksi_id" value="{{ $transaksi->id ?? '' }}">

        @if ($transaksi)
            <div class="row">
                <div class="col">
                <div class="form-group">
                    <label for="inputEmail3">Total</label>
                    <input type="number" class="form-control" id="total_brg" value="{{ $total }}" readonly>
                </div>
                </div>
                <div class="col">
                <div class="form-group">
                    <label for="inputEmail3">Bayar</label>
                    <input type="number" class="form-control" id="bayar_brg" min="1">
                </div>
                </div>
                <div class="col">
                <div class="form-group">
                    <label for="inputEmail3">Kembali</label>
                    <input type="number" class="form-control" id="kembali_brg" readonly>
                </div>
                </div>
            </div>

            <div class="d-flex">
                <button class="btn btn-success mr-2" id="closePayment" disabled>Bayar</button>
                <button class="btn btn-primary" id="cetakSlip" disabled>Print</button>
            </div>
        @endif
        

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
        let valueElement = document.getElementById('value');

        $(document).ready(function() {
            cekDataDetail();
            var selectedIds = [];
            var barangs = [];

            var table = $('#dtSearchBrg').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.search_products') }}",
                columns: [
                    { data: 'kode_barang', name: 'kode_barang', sortable: false },
                    { data: 'nama_barang', name: 'nama_barang', sortable: false },
                    { data: 'harga_jual', name: 'harga_jual', sortable: false },
                    { data: 'stok', name: 'stok', sortable: false },
                    { data: 'action', name: 'action', sortable: false },
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
                },
                initComplete: function () {
                    $('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc').addClass('no-sort');
                }
            });

            var table = $('#dtTransaksi').DataTable({
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
                        sortable: false,
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                        sortable: false,
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        sortable: false,
                    },
                    {
                        data: 'products.date_expired',
                        name: 'products.date_expired',
                        sortable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        sortable: false,
                    },
                ],
                drawCallback: function(settings) {

                    $('#dtTransaksi').off('click', '#incrementBtn').on('click', '#incrementBtn', function() {
                        var $this = $(this);
                        var id = $this.data('id');
                        var barangId = $this.data('barang');
                        var inputId = 'value_' + id;
                        var hargaJualId = 'harga_' + id;
                        var jumlah = parseInt($('#' + inputId).val());
                        var maxStok = parseInt($('#' + inputId).attr('max'));
                        var hargaItem = $('#' + hargaJualId);
                        var valueHargaItem = parseInt(hargaItem.val());
                        jumlah += 1;

                        if (jumlah > maxStok) {
                            alert('Jumlah melebihi stok maksimum.')
                            jumlah =  maxStok;
                        }

                        let existingItem = barangs.find(item => item.id === id);
                        if (existingItem) {
                            existingItem.jumlah_int = jumlah;
                            existingItem.subtotal_item = existingItem.harga_jual * jumlah;
                        }

                        $('#' + inputId).val(jumlah);

                        sumBuy();
                    });

                    $('#dtTransaksi').off('click', '#decrementBtn').on('click', '#decrementBtn', function() {
                        var $this = $(this);
                        var id = $this.data('id');
                        var barangId = $this.data('barang');
                        var hargaJualId = 'harga_' + id;
                        var hargaItem = $('#' + hargaJualId);
                        var valueHargaItem = parseInt(hargaItem.val());

                        var inputId = 'value_' + id;
                        var jumlah = parseInt($('#' + inputId).val());

                        if (jumlah > 0) {
                            jumlah -= 1;
                            $('#' + inputId).val(jumlah);
                        }

                        let existingItem = barangs.find(item => item.id === id);
                        if (existingItem) {
                            existingItem.jumlah_int = jumlah;
                            existingItem.subtotal_item = existingItem.harga_jual * jumlah;
                        }

                        if (jumlah == 0) {
                            var result = confirm('Anda Yakin Ingin Menghilangkan Item?');

                            if (result) {
                                $.ajax({
                                    url: '{{ route("transaksi.remove-item") }}',  // Ganti dengan endpoint Anda
                                    method: 'POST',
                                    data: {
                                        id: id,
                                        _token: '{{ csrf_token() }}' // Jika menggunakan Laravel, sertakan token CSRF
                                    },
                                    success: function(response) {
                                        alert(response.message)
                                        location.reload();
                                    },
                                    error: function(xhr) {
                                        alert('An error occurred: ' + xhr.responseText);
                                    }
                                });
                            } else {
                                location.reload();
                            }
                        }

                        sumBuy();
                    });

                    $('#dtTransaksi').off('click', '#btnRemove').on('click', '#btnRemove', function() {
                        var $this = $(this);
                        id = $this.data('id');
                        barangId = $this.data('barang');
                        var jumlah = 0; 
                        let transaksiId = $("#transaksi_id").val();

                        $.ajax({
                            url: '{{ route("transaksi.remove-item") }}',  // Ganti dengan endpoint Anda
                            method: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}' // Jika menggunakan Laravel, sertakan token CSRF
                            },
                            success: function(response) {
                                alert(response.message);
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('An error occurred: ' + xhr.responseText);
                            }
                        });
                    });
                },
                initComplete: function () {
                    $('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc').addClass('no-sort');
                }
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

            function cekDataDetail() {
                $.ajax({
                    url: '{{ route("transaksi.index") }}',  // Ganti dengan endpoint Anda
                    method: 'GET',
                    success: function(response) {
                        const datas = response.data;

                        datas.forEach(element => {
                            if (!barangs.find(item => item.id === element.id)) {
                                barangs.push(element);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        alert('Checkout gagal');
                    }
                });
            }

            function sumBuy() {
                let sum = 0;
                if (barangs) {
                    barangs.forEach(element => {
                        sum += element.subtotal_item
                    });
                }

                var totalBrg = $("#total_brg");
                var totalBayar = $("#bayar_brg");
                var totalKembalian = $("#kembali_brg");

                totalBrg.val(sum);

                let valueTotalBayar = totalBayar.val();
                if (valueTotalBayar > 0) {
                    var resultKembalian = totalBayar.val() - sum;
                    totalKembalian.val(resultKembalian);
                }
            }

            $('#bayar_brg').on('input', function() {
                var value = $(this).val();
                var valueTotalBrg = $("#total_brg").val();
                var valueKembalianBrg = $("#kembali_brg").val();

                if (value > 0) {
                    var kembalian = value - valueTotalBrg;
                    $("#kembali_brg").val(kembalian);
                } else {
                    $("#kembali_brg").val('');
                }
                
                if (value) {
                    $('#closePayment').removeAttr('disabled');
                    $('#cetakSlip').removeAttr('disabled');
                } else {
                    $('#closePayment').attr('disabled', 'disabled');
                    $('#cetakSlip').attr('disabled', 'disabled');
                }
            });

            $('#btnResetItem').on('click', function() {
                let transaksiId = $("#transaksi_id").val();
                $.ajax({
                    url: '{{ route("transaksi.reset-all-item") }}',  // Ganti dengan endpoint Anda
                    method: 'POST',
                    data: {
                        transaksi_id: transaksiId,
                        _token: '{{ csrf_token() }}' // Jika menggunakan Laravel, sertakan token CSRF
                    },
                    success: function(response) {
                        alert(response.message)
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                    }
                });
            });

            $('#closePayment').on('click', function() {
                let transaksiId = $("#transaksi_id").val();
                var valueTotalBrg = $("#total_brg").val();
                var valueBayarBrg = $("#bayar_brg").val();
                var valueKembaliBrg = $("#kembali_brg").val();

                let datas = [];
                barangs.forEach(element => {
                    datas.push({
                        detail_id: element.id,
                        quantity: element.jumlah_int,
                        subtotal: element.subtotal_item,
                    })
                });

                $.ajax({
                    url: '{{ route("transaksi.checkout-payment") }}',  // Ganti dengan endpoint Anda
                    method: 'POST',
                    data: {
                        transaksi_id: transaksiId,
                        total_brg: valueTotalBrg,
                        bayar_brg: valueBayarBrg,
                        kembali_brg: valueKembaliBrg,
                        array_detail_transaksi: datas,
                        _token: '{{ csrf_token() }}' // Jika menggunakan Laravel, sertakan token CSRF
                    },
                    success: function(response) {
                        alert(response.message)
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                    }
                });
            });

            $('#cetakSlip').on('click', function() {
                let transaksiId = $("#transaksi_id").val();
            });
        });
    </script>
@endsection