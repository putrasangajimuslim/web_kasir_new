@extends('layouts.app')

@section('title')
    {{ __('Halaman Edit Karyawan') }} | {{ config('app.name') }}
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
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Kehadiran</h6>

                <a href="{{ route('users.index') }}" class="btn btn-outline-info btn-fw"><i class="fas fa-arrow-left mr-2"></i> Back</a>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <form action="{{ route('users.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="InputNama">Nama  <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="InputNama" name="nama" value="{{ old('nama', $user->nama) }}">
                        <input type="hidden" name="id_users" value="{{ $user->id }}">
                        @error('nama')
                            <span style="color: red;">Silahkan Isi Nama</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputEmail">Email  <span style="color: red">*</span></label>
                        <input type="email" class="form-control" id="InputEmail" name="email" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <span style="color: red;">Silahkan Isi Email</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputTglLahir">Tanggal Lahir  <span style="color: red">*</span></label>
                        <input type="date" class="form-control" id="InputTglLahir" name="tgl_lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}">
                        @error('tgl_lahir')
                            <span style="color: red;">Silahkan Isi Tanggal Lahir</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputNoHp">No Handphone  <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="InputNoHp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                        @error('no_hp')
                            <span style="color: red;">Silahkan Isi No Handphone</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputAlamat">Alamat <span style="color: red">*</span></label>
                        <textarea name="alamat" id="InputAlamat" cols="20" rows="10" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <span style="color: red;">Silahkan Isi Alamat</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="InputJenisKel">Jenis Kelamin <span style="color: red">*</span></label>
                        <select name="jenis_kelamin" id="InputJenisKel" class="form-control">
                            <option value="">--Please Select Jenis Kelamin--</option>
                            <option value="L" {{ $user->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ $user->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span style="color: red;">Silahkan Pilih Jenis Kelamin</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputRole">Role <span style="color: red">*</span></label>
                        <select name="role" id="InputRole" class="form-control">
                            <option value="">--Please Select Role--</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                        @error('role')
                            <span style="color: red;">Silahkan Pilih Role</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputStatus">Status <span style="color: red">*</span></label>
                        <select name="status" id="InputStatus" class="form-control">
                            <option value="">--Please Select Status--</option>
                            <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Belum Aktif</option>
                        </select>
                        @error('status')
                            <span style="color: red;">Silahkan Pilih Status</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="InputPass">Password </label>
                        <input type="password" class="form-control" id="InputPass" name="password" value="{{ old('password') }}">
                        @error('password')
                            <span style="color: red;">Silahkan Isi Password</span>
                        @enderror
                    </div>
                    <button class="btn btn-success btn-block" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection