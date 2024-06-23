@extends('layouts.admin')

@section('content')
    <div class="page-wrapper" >
        <div class="mt-5 mx-5">
            <div class="row d-flex justify-content-between">
                <div class="mb-5">
                    <h1>Jurnal Harian</h1>
                    <h3 id="humanTime">{{ $human_time }}</h3>
                </div>
                <div class="">
                    <label for="" class="form-label">Ganti Tanggal</label>
                    <input type="date" name="" id="date" class="form-control">
                    <button class="btn btn-primary my-3" data-toggle="modal"
                    data-target="#modal-tambah">Tambah Laporan Jurnal</button>
                </div>
            </div>

            {{-- <div class="row">
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
            </div> --}}
            <div class="">
                <a href="{{ route('jurnal_harian_print') }}" target="_blank" ><button class="btn btn-primary mb-3" id="print">Print Jurnal Harian</button></a>
              </div>
            <div class="row" style="position: relative; overflow-x: auto;">
                <table id="zero_confi" class="table table-striped table-bordered no-wrap">
                    <thead class="text-center"> 
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col" colspan="4">Bakso Polos</th>
                            <th scope="col" colspan="4">Bakso Urat</th>
                            <th scope="col" colspan="4">Bakso Daging</th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Cash Lapangan</th>
                            <th scope="col">Cash Laporan</th>
                            <th scope="col">Dandang</th>
                            <th scope="col">Freezer Depan</th>
                            <th scope="col">Freezer Belakang</th>
                            <th scope="col">Minus</th>
                            <th scope="col">Dandang</th>
                            <th scope="col">Freezer Depan</th>
                            <th scope="col">Freezer Belakang</th>
                            <th scope="col">Minus</th>
                            <th scope="col">Dandang</th>
                            <th scope="col">Freezer Depan</th>
                            <th scope="col">Freezer Belakang</th>
                            <th scope="col">Minus</th>
                        </tr>
                    </thead>
                    <tbody id="report">
                        @foreach ($data as $key => $transaksi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Rp. {{ number_format($transaksi->jml_cash_lapangan, "0", ",", ",") }}</td>
                                <td>Rp. {{ number_format($transaksi->jml_cash_laporan, "0", ",", ",") }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'dandang')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer depan')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 1)->where('lokasi', 'freezer belakang')->first()->qty }}</td>
                                <td>-{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 1)->first()->minus }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'dandang')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer depan')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 2)->where('lokasi', 'freezer belakang')->first()->qty }}</td>
                                <td>-{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 2)->first()->minus }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'dandang')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer depan')->first()->qty }}</td>
                                <td>{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 3)->where('lokasi', 'freezer belakang')->first()->qty }}</td>
                                <td>-{{ \App\Models\stok_barang_jurnal_harian::where('jurnal_harian_id', $transaksi->id)->where('bahan_setengah_jadi_id', 3)->first()->minus }}</td>
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
                                    <h4 class="card-title">Tambahkan Laporan Jurnal</h4>
                                    <form method="POST" action="{{ route('jurnal_harian.store') }}"
                                        enctype="multipart/form-data" class="mt-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Jumlah Cash Lapangan</label>
                                            <input type="text" name="cash_lapangan" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Jumlah Cash Laporan</label>
                                            <input type="text" name="cash_laporan" class="form-control border-primary"
                                                >
                                        </div>
                                        <label for="">Bakso Urat</label>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name">Di Dandang</label>
                                            <input type="hidden" name="bu_lokasi[]" value="dandang">
                                            <input type="text" name="bu_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Belakang</label>
                                            <input type="hidden" name="bu_lokasi[]" value="freezer belakang">
                                            <input type="text" name="bu_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Depan</label>
                                            <input type="hidden" name="bu_lokasi[]" value="freezer depan">
                                            <input type="text" name="bu_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Minus Stok</label>
                                            <input type="number" name="bu_minus" class="form-control border-primary"
                                                required>
                                        </div>
                                        <label for="">Bakso Polos</label>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name">Di Dandang</label>
                                            <input type="hidden" name="bp_lokasi[]" value="dandang">
                                            <input type="text" name="bp_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Belakang</label>
                                            <input type="hidden" name="bp_lokasi[]" value="freezer belakang">
                                            <input type="text" name="bp_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Depan</label>
                                            <input type="hidden" name="bp_lokasi[]" value="freezer depan">
                                            <input type="text" name="bp_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Minus Stok</label>
                                            <input type="number" name="bp_minus" class="form-control border-primary"
                                                required>
                                        </div>
                                        <label for="">Bakso Daging</label>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name">Di Dandang</label>
                                            <input type="hidden" name="bd_lokasi[]" value="dandang">
                                            <input type="text" name="bd_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Belakang</label>
                                            <input type="hidden" name="bd_lokasi[]" value="freezer belakang">
                                            <input type="text" name="bd_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Di Freezer Depan</label>
                                            <input type="hidden" name="bd_lokasi[]" value="freezer depan">
                                            <input type="text" name="bd_qty[]" class="form-control border-primary"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Minus Stok</label>
                                            <input type="number" name="bd_minus" class="form-control border-primary"
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
                url: "{{ route('jurnal_admin') }}",
                dataType: "JSON",
                data: {
                    date : $("#date").val(),
                },
                success: function(data) {
                    html = "";
                        html = `
                            <tr>
                                <td>${1}</td>
                                <td>${(data.data != null) ? data.data.jml_cash_laporan : ""}</td>
                                <td>${(data.data != null) ? data.data.jml_cash_lapangan :""}</td>
                                <td>${data.bp_dandang}</td>
                                <td>${data.bp_freezer_depan}</td>
                                <td>${data.bp_freezer_belakang}</td>
                                <td>${data.bp_minus}</td>
                                <td>${data.bu_dandang}</td>
                                <td>${data.bu_freezer_depan}</td>
                                <td>${data.bu_freezer_belakang}</td>
                                <td>${data.bu_minus}</td>
                                <td>${data.bd_dandang}</td>
                                <td>${data.bd_freezer_depan}</td>
                                <td>${data.bd_freezer_belakang}</td>
                                <td>${data.bd_minus}</td>
                            </tr>
                        `

                    $("#report").html(html);
                    $("#humanTime").html(data.human_time);
                }
            });
        });
    });
</script>