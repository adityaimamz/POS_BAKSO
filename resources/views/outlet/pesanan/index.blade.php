@extends('layouts.outlet')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Daftar Pesanan</h4>
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
                            @foreach ($transaksi as $index => $item)
                            @if ($index == 0)
                            <h4 class="card-title">Pesanan Dikerjakan</h4>
                            <h4>Nama Pelanggan : {{ $item->name_customer }}</h4>
                            <h4>No. Meja : {{ ($item->table->number) ?? "" }}</h4>
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered no-wrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pesanan</th>
                                            <th>Pilihan</th>
                                            <th>Tanggal transaksi</th>
                                            <th>Selesaikan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-table">
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @foreach (\App\Models\Transaction_detail::where('transaction_id', $item->id)->where('order_status', 'Diproses')->get() as $transaction_detail)
                                                    <div class="d-flex justify-content-between mb-3 font-20">
                                                        @if (\App\Models\Produk::where('id', $transaction_detail->product_id)->where('location_id', '!=', null)->first())
                                                            <span>
                                                                {{ \App\Models\Produk::where('id', $transaction_detail->product_id)->where('location_id', '!=', null)->first()->name }}
                                                            </span>
                                                            <span>
                                                                x{{ $transaction_detail->qty }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-muted">Pesan : {{ $transaction_detail->note }}</p>
                                                    <hr style="border: 1px solid grey">
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach (\App\Models\Transaction_detail::where('transaction_id', $item->id)->where('order_status', 'Diproses')->get() as $transaction_detail)
                                                    <div class="d-flex justify-content-between mb-3 font-20">
                                                        @if (\App\Models\Produk::where('id', $transaction_detail->product_id)->where('location_id', '!=', null)->first())
                                                            <span>{{ $transaction_detail->order_type }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-transparant">.</p>
                                                    <hr style="border: 1px solid grey">
                                                    @endforeach
                                                </td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <a href="" data-toggle="modal"
                                                        data-target="#modal-edit{{ $item->id }}" style="width: 50px"
                                                        class="btn btn-success"><i class="bi bi-pencil"><span
                                                                class="fas fa-check"></span></i></a>
                                                </td>
                                            </tr>
                                            <div id="modal-edit{{ $item->id }}" class="modal fade" tabindex="-1"
                                                role="dialog" aria-labelledby="modal-editLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header modal-colored-header bg-primary">
                                                            <h4 class="modal-title" id="modal-editLabel">Selesaikan Pesanan
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h4 class="px-5 text-center mb-5"><strong>Apakah Akan Menyelesaikan Pesanan?</strong></h4>
                                                            <h1 class="px-5 text-center text-primary "><span class="fas fa-check-circle"></span></strong></h4>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary ms-auto" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('pesanan.update', $item->id) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <button class="btn btn-primary" type="submit">Lanjutkan</button>
                                                        </form>
                                                        </div>

                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Antrian Pesanan</h4>
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Tanggal transaksi</th>
                                            <th>No Meja</th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-table">
                                        @foreach ($transaksi as $index => $item)
                                            @if ($index > 0)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>{{ $item->name_customer }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ ($item->table->number) ?? "" }}</td>
                                                </tr>
                                            @else
                                            @endif
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
                        <h4 class="modal-title" id="modal-tambahLabel">Form Tambah Produl
                        </h4>
                        <button type="button" class="close" transaction-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambahkan Akun Cabang</h4>
                                    <form method="POST" action="{{ route('accounts.store') }}"
                                        enctype="multipart/form-transaction" class="mt-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" name="name" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Email</label>
                                            <input type="email" name="email" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Password</label>
                                            <input type="password" name="password" class="form-control border-primary"
                                                required>
                                        </div>
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
