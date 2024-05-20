@extends('layouts.app')

@section('title')
    {{ __('Halaman Karyawan') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Users</h6>
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


        <a href="{{ route('users.create') }}" class="btn btn-outline-success btn-fw mb-4">Tambah Users</a>
    
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Karyawan</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Status</th>
                        <th>No Handphone</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    @php
                        $status = '';
                        if ($user->status == 1) {
                            $status = 'Aktif';
                        } else {
                            $status = 'Belum Aktif';
                        }

                        $gender = '';
                        if ($user->jenis_kelamin == 'L') {
                            $gender = 'Laki-Laki';
                        } else {
                            $gender = 'Perempuan';
                        }
                        
                    @endphp
                    <tr>
                        <td>{{ $user->kode_karyawan }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->tgl_lahir }}</td>
                        <td>{{ $status }}</td>
                        <td>{{ $user->no_hp }}</td>
                        <td>{{ $user->alamat }}</td>
                        <td>{{ $gender }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="{{ route('users.edit', ['id'=> $user->id]) }}"  class="btn btn-primary" id="btnEdit">Edit</a>
                            <button class="btn btn-danger btnDel" id="btnEdit" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('users.destroy', ['id' => $user->id]) }}"><i class="fas fa-trash"></i></button>


                            @if ($user->status == 0)
                                <a href="{{ route('users.aktivasi-akun', ['id'=> $user->id]) }}"  class="btn btn-success" id="btnAktif">Aktif</a>
                            @endif
                        </td>
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