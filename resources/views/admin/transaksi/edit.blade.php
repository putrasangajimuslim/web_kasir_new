@extends('layouts.app')

@section('title')
    {{ __('Halaman Edit Barang') }} | {{ config('app.name') }}
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
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Barang</h6>

                <a href="{{ route('products.index') }}" class="btn btn-outline-info btn-fw"><i class="fas fa-arrow-left mr-2"></i> Back</a>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <form action="{{ route('products.update') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $barang->id }}" name="product_id">

                    <div class="form-group">
                        <label for="InputNamaBarang">Nama Barang <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="InputNamaBarang" name="name" value="{{ old('name', $barang->nama_barang) }}">
                        @error('name')
                            <span style="color: red;">Silahkan Isi Nama Barang</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="InputCategory">Kategori <span style="color: red">*</span></label>
                        <select name="kategori_id" id="InputCategory" class="form-control">
                            <option value="">-- Silahkan Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                @if (old('kategori_id') == $category->id || $barang->kategori_id == $category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->nama }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <span style="color: red;">Silahkan Pilih Kategori</span>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="InputMerk">Merk <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="InputMerk" name="merk" value="{{ old('merk', $barang->merk) }}">
                        @error('merk')
                            <span style="color: red;">Silahkan Isi Merk</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputHargaBeli">Harga Beli <span style="color: red">*</span></label>
                        <input type="number" class="form-control" id="InputHargaBeli" name="harga_beli" value="{{ $barang->harga_beli }}">
                        @error('harga_beli')
                            <span style="color: red;">Silahkan Isi Harga Beli</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputHargajual">Harga Jual <span style="color: red">*</span></label>
                        <input type="number" class="form-control" id="InputHargajual" name="harga_jual" value="{{ $barang->harga_jual }}">
                        @error('harga_jual')
                            <span style="color: red;">Silahkan Isi Harga Jual</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputStok">Stok</label>
                        <input type="number" class="form-control" id="InputStok" name="stok" value="{{ old('stok', $barang->stok) }}">
                    </div>

                    <div class="form-group">
                        <label for="masa_exp">Masa Expired <span style="color: red">*</span></label>
                        <input type="date" class="form-control" id="masa_exp" name="masa_exp" value="{{ old('masa_exp', $barang->date_expired) }}">
                        @error('masa_exp')
                            <span style="color: red;">Silahkan Isi Masa Expired</span>
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
            // let hargaBeli = 0;
            // let margin = 0;

            // $('#InputSatuanbarang').on('input', function() {
            //     var inputValue = $(this).val();
            //     var sanitizedValue = inputValue.replace(/[^a-zA-Z]/g, '');
            //     $(this).val(sanitizedValue);
            // });

            // $('#InputHargaBeli').on('input', function() {
            //     hargaBeli = parseFloat($(this).val()) || 0;
            //     updateHargaJual();
            // });

            // $('#InputMargin').on('input', function() {
            //     margin = parseFloat($(this).val()) || 0;
            //     updateHargaJual();
            // });

            // function updateHargaJual() {
            //     let keuntungan = hargaBeli * (margin / 100);
            //     let hargaJual = hargaBeli + keuntungan;

            //     $("#InputHargajual").val(hargaJual);
            // }
        });

    </script>
@endsection