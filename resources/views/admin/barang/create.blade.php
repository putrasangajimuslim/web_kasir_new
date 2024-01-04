@extends('layouts.app')

@section('title')
    {{ __('Halaman Tambah Barang') }} | {{ config('app.name') }}
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
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Barang</h6>

                <a href="{{ route('products.index') }}" class="btn btn-outline-info btn-fw"><i class="fas fa-arrow-left mr-2"></i> Back</a>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="InputNamaBarang">Nama Barang </label>
                        <input type="text" class="form-control" id="InputNamaBarang" name="name" value="{{ old('name') }}">
                        @error('name')
                            <span style="color: red;">Silahkan Isi Nama Barang</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputCategory">Kategori <span style="color: red">*</span></label>
                        <select name="id_kategori" id="InputCategory" class="form-control">
                            <option value="">-- Please Select Kategori --</option>
                            @foreach ($categories as $category)
                                @if (old('id_kategori') === $category->kode_karyawan)
                                    <option value="{{ $category->kode_karyawan }}" selected>{{ $category->kode_karyawan }} - {{ $category->nama }}</option>
                                @else
                                    <option value="{{ $category->kode_karyawan }}">{{ $category->kode_karyawan }} - {{ $category->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('kode_karyawan')
                            <span style="color: red;">Silahkan Pilih Kode Karyawan</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputMerk">Merk </label>
                        <input type="text" class="form-control" id="InputMerk" name="merk" value="{{ old('merk') }}">
                        @error('merk')
                            <span style="color: red;">Silahkan Isi Merk</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputHargaBeli">Harga Beli </label>
                        <input type="number" class="form-control" id="InputHargaBeli" name="harga_beli" value="{{ old('harga_beli') }}">
                        @error('harga_beli')
                            <span style="color: red;">Silahkan Harga Beli</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputHargajual">Harga Jual </label>
                        <input type="number" class="form-control" id="InputHargajual" name="harga_jual" value="{{ old('harga_jual') }}">
                        @error('harga_jual')
                            <span style="color: red;">Silahkan Harga Jual</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputMargin">Margin Keuntungan</label>
                        <input type="text" class="form-control" id="InputMargin" name="margin_keuntungan" value="{{ old('margin_keuntungan') }}">
                        @error('margin_keuntungan')
                            <span style="color: red;">Silahkan Margin Keuntungan</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputSatuanbarang">Satuan Barang</label>
                        <input type="text" class="form-control" id="InputSatuanbarang" name="satuan_barang" value="{{ old('satuan_barang') }}">
                        @error('satuan_barang')
                            <span style="color: red;">Silahkan Satuan Barang</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputStok">Stok</label>
                        <input type="text" class="form-control" id="InputStok" name="stok" value="{{ old('stok') }}">
                        @error('stok')
                            <span style="color: red;">Silahkan Stok</span>
                        @enderror
                    </div>
                    
                    <button class="btn btn-success btn-block" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection