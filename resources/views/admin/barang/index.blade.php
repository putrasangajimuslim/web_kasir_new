@extends('layouts.app')

@section('title')
    {{ __('Halaman Barang') }} | {{ config('app.name') }}
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Barang</h6>
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

        @if ($role == 'admin')
            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-fw mb-4">Tambah Barang</a>    
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dtBarangs" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Masa Expired</th>
                        <th>Status Layak</th>
                        <th>Stok</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-delete-label">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a id="confirm-delete" href="#" class="btn btn-danger">Yes</a>
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
            $('#modal-delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var href = button.data('href'); // Extract info from data-* attributes
                var modal = $(this);
                modal.find('#confirm-delete').attr('href', href);
            });

            var table = $('#dtBarangs').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    { data: 'kode_barang', name: 'kode_barang', sortable: false },
                    { data: 'nama_barang', name: 'nama_barang', sortable: false },
                    { data: 'merk', name: 'merk', sortable: false },
                    { data: 'harga_beli', name: 'harga_beli', sortable: false },
                    { data: 'harga_jual', name: 'harga_jual', sortable: false },
                    { data: 'date_expired', name: 'date_expired', sortable: false },
                    { data: 'status_exp', name: 'status_exp', sortable: false },
                    { data: 'stok', name: 'stok', sortable: false },
                    { data: 'action', name: 'action', sortable: false },
                ],
                initComplete: function () {
                    $('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc').addClass('no-sort');
                }
            });
        });
    </script>
@endsection