@extends('layouts.app')

@section('title')
    {{ __('Halaman Update Profile') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
        <div class="card-body"><div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User Profile</h6>
        </div>
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

        <div class="container">
            <div class="mt-4">
                <form action="{{ route('user-profile.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="inputKaryawan" class="col-sm-2 col-form-label">Kode Karyawan</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control-plaintext" id="inputKaryawan" value="{{ $user->kode_karyawan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control-plaintext" id="inputUsername" value="{{ $user->nama }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail" value="{{ old('email', $user->email) }}" name="email">
                        </div>
                        @error('email')
                            <span style="color: red; margin: 10px 0px 10px 10px;">Silahkan Isi Email Yang Sesuai</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label for="inputBirthday" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="inputBirthday" value="{{ old('birthday', $user->tgl_lahir) }}" name="birthday">
                        </div>
                        @error('birthday')
                            <span style="color: red; margin: 10px 0px 10px 10px;">Silahkan Isi Tanggal Lahir</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label for="inputNohp" class="col-sm-2 col-form-label">No Handphone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputNohp" value="{{ old('no_hp', $user->no_hp) }}" name="no_hp">
                        </div>
                        @error('no_hp')
                            <span style="color: red; margin: 10px 0px 10px 10px;">Silahkan Isi No Handphone</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label for="inputAlamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputAlamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        @error('alamat')
                            <span style="color: red; margin: 10px 0px 10px 10px;">Silahkan Isi Alamat</span>
                        @enderror
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection