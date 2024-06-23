<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Admin Bakso Liktono</title>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/styletambahan.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body onload="generatePDF()">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->


    <div class="page-wrapper">
        <div class="mt-5 mx-5">
            <div class="row d-flex justify-content-between">
                <div class="mb-5">
                    <h1>Laporan Rekapitulasi Harian</h1>
                    <h3 id="humanTime">{{ $human_time }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="d-flex flex-wrap">
                    <div class="card border-right" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="pendapatan">Rp.
                                            {{ $revenue }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pendapatan
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right ml-3" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="cash">Rp.
                                            {{ $earningCash }}
                                        </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari
                                        Cash
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right ml-3" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="qris">Rp.
                                            {{ $earningQris }}
                                        </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari
                                        Qris
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right ml-3" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="bank">Rp.
                                            {{ $earningBank }}
                                        </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari
                                        bank
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-danger border-right ml-3" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-danger mb-1 font-weight-medium" id="minus">- Rp.
                                            {{ $minus }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-danger">Minus Penjualan
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-danger border-right ml-3" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-danger mb-1 font-weight-medium" id="salah">- Rp. {{ $salah }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-danger">Transaksi Salah</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted text-danger"><i data-feather="dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-5" style="position: relative; overflow-x:scroll;">
                <table id="zero_confi" class="table table-striped table-bordered no-wrap">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Penanggung Jawab</th>
                            <th scope="col" colspan="3">Pesanan</th>
                            <th scope="col">Total Bayar</th>
                            <th scope="col">Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody id="report">
                        @foreach ($data as $key => $transaksi)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $transaksi->name_customer }}</td>
                                <td>{{ \App\Models\User::where('id', $transaksi->user_id)->first()->name }}</td>
                                <td>
                                    @foreach (\App\Models\Transaction_detail::where('transaction_id', $transaksi->id)->get() as $item)
                                        <p>{{ \App\Models\Produk::where('id', $item->product_id)->first()->name }}</p>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (\App\Models\Transaction_detail::where('transaction_id', $transaksi->id)->get() as $item)
                                        <p>{{ $item->qty }}</p>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (\App\Models\Transaction_detail::where('transaction_id', $transaksi->id)->get() as $item)
                                        <p>{{ \App\Models\Produk::where('id', $item->product_id)->first()->price * $item->qty }}
                                        </p>
                                    @endforeach
                                </td>
                                <td>{{ number_format($transaksi->pay_amount, 0, ',', ',') }}</td>
                                <td>{{ $transaksi->payment->name ?? 'Belum Bayar' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js"></script>
    <script>
        function generatePDF() {
            const element = document.body; // Ganti dengan elemen yang ingin Anda konversi ke PDF

            html2pdf(element, {
                margin: 10,
                filename: 'rekap harian.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a2',
                    orientation: 'portrait'
                }
            });

        }
    </script>



    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!--This page JavaScript -->
    <script src="{{ asset('assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboards/dashboard1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ asset('assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
</body>

</html>
