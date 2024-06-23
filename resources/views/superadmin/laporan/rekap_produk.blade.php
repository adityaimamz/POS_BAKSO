@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="mt-5 mx-5">
        <div class="row d-flex justify-content-between">
            <div class="mb-5">
                <h1>Laporan Rekapitulasi Produk</h1>

                {{-- <h3 id="humanTime">{{ $human_time }}</h3> --}}
            </div>
            <div class="">
                <label for="" class="form-label">Ganti Tanggal</label>
                <input type="date" name="" id="date" class="form-control">
            </div>
        </div>
        <h3 class="text-dark mb-1 font-weight-medium">Jumlah Stok Awal</h3>
        <div class="row">
            <div class="d-flex flex-wrap">
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="stok_awal_polos">{{ ($stok_awal[0]) ?? 0 }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Polos</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="stok_awal_urat">{{ ($stok_awal[1]) ?? 0 }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Urat</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="stok_awal_daging">{{ ($stok_awal[2]) ?? 0 }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Daging</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="text-dark mb-1 font-weight-medium">Jumlah Penjualan</h3>
        <div class="row">
            <div class="d-flex flex-wrap">
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="polos">{{ $jml_polos[0]->bakso_polos }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Polos</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="urat">{{ $jml_polos[0]->bakso_urat }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Urat</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="daging">{{ $jml_polos[0]->bakso_daging }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Daging</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="text-dark mb-1 font-weight-medium">Sisa Stok</h3>
        <div class="row">
            <div class="d-flex flex-wrap">
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="sisa_polos">{{ (($stok_awal[0] ?? 0) - $jml_polos[0]->bakso_polos) }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Polos</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="sisa_urat">{{ (($stok_awal[1] ?? 0)  - $jml_polos[0]->bakso_urat) }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Urat</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right" style="width: 20rem">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium" id="sisa_daging">{{ (($stok_awal[2] ?? 0) - $jml_polos[0]->bakso_daging) }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Bakso Daging</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <a href="{{ route('print_rekap_produk') }}" target="_blank" ><button class="btn btn-primary mb-3" id="print">Print Rekap Produk</button></a>
          </div>
        <div class="row pb-5" style="position: relative; overflow-x:scroll;">
            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                <thead class="text-center"> 
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Terjual</th>
                    </tr>
                </thead>
                <tbody id="report">
                    @foreach ($data as $key => $transaksi)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $transaksi['menu'] }}</td>
                            <td>{{ $transaksi['porsi'] }} Porsi</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // var date = "{{ date('Y-m-d') }}";
        $("#date").on("change", function() {
            $.ajax({
            type: 'GET',
            url: "{{ route('rekap_produk_superadmin') }}",
            dataType: "JSON",
            data: {
                date : $("#date").val(),
            },
            success: function(data) {
                // console.log(data);
                html = "";
                $.each(data.data, function(i, item) {
                    html += `
                        <tr>
                            <th scope="row">${i+1}</th>
                            <td>${item.menu}</td>
                            <td>${item.porsi}</td>
                        </tr>
                    `
                }); 
                $("#report").html(html);

                $("#polos").html(data.jml_bakso[0].bakso_polos);   
                $("#urat").html(data.jml_bakso[0].bakso_urat);   
                $("#daging").html(data.jml_bakso[0].bakso_daging);  

                $("#stok_awal_polos").html((data.stok_awal != "") ? data.stok_awal[0] : "");
                $("#stok_awal_urat").html((data.stok_awal != "") ? data.stok_awal[1] : "");
                $("#stok_awal_daging").html((data.stok_awal != "") ? data.stok_awal[2] : "");

                $("#sisa_polos").html((((data.stok_awal != null) ? data.stok_awal[0] : 0) - data.jml_bakso[0].bakso_polos));
                $("#sisa_urat").html((((data.stok_awal != null) ? data.stok_awal[1] : 0) - data.jml_bakso[0].bakso_urat));
                $("#sisa_daging").html((((data.stok_awal != null) ? data.stok_awal[2] : 0) - data.jml_bakso[0].bakso_daging));
            }
        });
        });


        // $("#print").on("click", function() {
        //     var newDate = date; // Tentukan nilai baru yang ingin Anda set
        //     $.ajax({
        //         type: 'GET',
        //         url: "{{ route('print_rekap_produk') }}", // Tentukan URL di mana Anda ingin menangani pembaruan nilai variabel $date di sisi server
        //         data: {
        //             date: newDate // Kirim data nilai baru ke server
        //         },
        //         success: function(response) {
        //             console.log(response); 
        //         }
        //     });
        // });

    });
</script>