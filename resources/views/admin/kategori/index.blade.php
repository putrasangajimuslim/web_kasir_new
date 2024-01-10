@extends('layouts.app')

@section('title')
    {{ __('Halaman Kategori') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Kategori</h6>
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


        @if ($isAdminAccess)
            <a href="{{ route('kategori.create') }}" class="btn btn-outline-success btn-fw mb-4">Tambah Kategori</a>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        @if ($isAdminAccess)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->nama }}</td>
                        @if ($isAdminAccess)
                            <td>
                                <a href="{{ route('kategori.edit', ['id'=> $category->id]) }}"  class="btn btn-primary" id="btnEdit">Edit</a>
                                <button class="btn btn-danger btnDel" id="btnEdit" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('kategori.destroy', ['id' => $category->id]) }}"><i class="fas fa-trash"></i></button>

                            </td>
                        @endif
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