@extends('layouts.app')

@section('title')
    {{ __('Halaman Tambah Kategori') }} | {{ config('app.name') }}
@endsection

@section('content')

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

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Kategori</h6>

                <a href="{{ route('kategori.index') }}" class="btn btn-outline-info btn-fw"><i class="fas fa-arrow-left mr-2"></i> Back</a>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="InputNamaBarang">Nama Kategori <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="InputNamaBarang" name="name" value="{{ old('name') }}">
                        @error('name')
                            <span style="color: red;">Silahkan Isi Nama Barang</span>
                        @enderror
                    </div>
                    
                    <button class="btn btn-success btn-block" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let hargaBeli = 0;
            let margin = 0;

            $('#InputSatuanbarang').on('input', function() {
                var inputValue = $(this).val();
                var sanitizedValue = inputValue.replace(/[^a-zA-Z]/g, '');
                $(this).val(sanitizedValue);
            });

            $('#InputHargaBeli').on('input', function() {
                hargaBeli = parseFloat($(this).val()) || 0;
                updateHargaJual();
            });

            $('#InputMargin').on('input', function() {
                margin = parseFloat($(this).val()) || 0;
                updateHargaJual();
            });

            function updateHargaJual() {
                let keuntungan = hargaBeli * (margin / 100);
                let hargaJual = hargaBeli + keuntungan;

                $("#InputHargajual").val(hargaJual);
            }
        });

    </script>
@endsection