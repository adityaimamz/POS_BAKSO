@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class="mt-5 mx-5">
            <div class="row d-flex justify-content-between">
                <div class="mb-5">
                    <h1>Laporan Pengeluaran</h1>
                    <h3 id="humanTime">{{ $human_time }}</h3>
                </div>
                <div class="">
                    <label for="" class="form-label">Ganti Tanggal</label>
                    <input type="date" name="" id="date" class="form-control">
                    <button class="btn btn-primary my-3" data-toggle="modal"
                    data-target="#modal-tambah">Tambah Pengeluaran</button>
                </div>
            </div>

            <div class="row">
                <div class="d-flex flex-wrap">
                    <div class="card border-right" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="pengeluaran">Rp. {{number_format($data->sum('amount'), 0, ",", ",") }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengeluaran</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="position: relative; overflow-x:scroll;">
                <table id="zero_config" class="table table-striped table-bordered no-wrap">
                    <thead class="text-center"> 
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody id="report">
                        @foreach ($data as $key => $transaksi)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $transaksi->name }}</td>
                                <td>{{ $transaksi->qty }}</td>
                                <td>{{ number_format($transaksi->amount, 0, ",", ",") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-tambahLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title" id="modal-tambahLabel">Form Tambah Pengeluaran
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambahkan Pengeluaran</h4>
                                    <form method="POST" action="{{ route('pengeluaran_harian.store') }}"
                                        enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" name="name" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Jumlah (optional)</label>
                                            <input type="text" name="qty" class="form-control border-primary"
                                                >
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Total Bayar</label>
                                            <input type="text" name="amount" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $("#date").on("change", function() {
            $.ajax({
                type: 'GET',
                url: "{{ route('pengeluaran_admin') }}",
                dataType: "JSON",
                data: {
                    date : $("#date").val(),
                },
                success: function(data) {
                    html = "";
                    $.each(data.data, function(i, item) {
                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${item.name}</td>
                                <td>${item.qty}</td>
                                <td>${item.amount.toLocaleString('id-ID')}</td>
                            </tr>
                        `
                    });

                    $("#report").html(html);
                    $("#humanTime").html(data.human_time);
                    $("#pengeluaran").html(`Rp. ${data.pengeluaran}`);
                }
            });
        });
    });
</script>