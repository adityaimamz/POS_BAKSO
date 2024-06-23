@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Data Stok Harian</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="/superadmin" class="text-muted">Home</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">{{ Request::segment(2) }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modal-tambah">Tambah Stok Harian</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <!-- basic table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">List Of Stok</h4>
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->barang_stok->name }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->location->locations }}</td>
                                            <td>
                                                <a href="" data-toggle="modal"
                                                    data-target="#modal-edit{{ $item->id }}" style="width: 50px"
                                                    class="btn btn-warning"><i class="bi bi-pencil"><span
                                                            class="fas fa-edit"></span></i></a>
                                                <form action="{{ route('stok_harian.destroy', $item->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" style="width: 50px" class="btn btn-danger"><i
                                                            class="bi bi-trash3">
                                                            <span class="fas fa-trash-alt"></span></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <div id="modal-edit{{ $item->id }}" class="modal fade" tabindex="-1"
                                            role="dialog" aria-labelledby="modal-editLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-colored-header bg-primary">
                                                        <h4 class="modal-title" id="modal-editLabel">Form Edit Stok
                                                        </h4>
                                                        <button type="reset" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Edit Stok</h4>
                                                                    <form method="POST"
                                                                        action="{{ route('stok_harian.update', $item->id) }}"
                                                                        enctype="multipart/form-data" class="mt-4">
                                                                        @method('PUT')
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="name">{{ $item->barang_stok->name }}</label>
                                                                            <input type="hidden" name="name" value="{{ $item->barang_stok_id }}">
                                                                            <input type="number" name="qty" value="{{ $item->qty }}" class="form-control border-primary"
                                                                                required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submiy"
                                                                                class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- order table -->
        <!-- ============================================================== -->
    </div>
    <div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-tambahLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="modal-tambahLabel">Form Tambah Stok
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tambahkan Stok</h4>
                                <form method="POST" action="{{ route('stok_harian.store') }}"
                                    enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    @foreach ($barang_stok as $item)
                                        <div class="form-group">
                                            <label for="name">{{ $item->name }}</label>
                                            <input type="hidden" name="name[]" value="{{ $item->id }}">
                                            <input type="number" name="qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                    @endforeach
                                    <div class="modal-footer">
                                        <button type="reset" class="btn btn-light">Kosongkan</button>
                                        <button type="submiy" class="btn btn-primary">Tambahkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@endsection