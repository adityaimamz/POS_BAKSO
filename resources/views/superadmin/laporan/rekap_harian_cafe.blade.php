@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class="mt-5 mx-5">
            <div class="row d-flex justify-content-between">
                <div class="mb-5">
                    <h1>Laporan Rekapitulasi Harian Cafe</h1>
                    <h3 id="humanTime">{{ $human_time }}</h3>
                </div>
                <div class="">
                    <label for="" class="form-label">Ganti Tanggal</label>
                    <input type="date" name="" id="date" class="form-control">
                </div>

            </div>
            <div class="row">
                <div class="d-flex flex-wrap">
                    <div class="card border-right" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium" id="pendapatan">Rp. {{$revenue }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pendapatan</h6>
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
                                        <h2 class="text-dark mb-1 font-weight-medium" id="cash">Rp. {{ $earningCash }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari Cash</h6>
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
                                        <h2 class="text-dark mb-1 font-weight-medium" id="qris">Rp. {{ $earningQris }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari Qris</h6>
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
                                        <h2 class="text-dark mb-1 font-weight-medium" id="bank">Rp. {{ $earningBank }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pendapatan dari bank</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-danger border-right" style="width: 20rem">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center justify-content-between">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-danger mb-1 font-weight-medium" id="minus">- Rp. {{ $minus }}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-danger">Minus Penjualan</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted text-danger"><i data-feather="dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-danger border-right" style="width: 20rem">
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
            <div class="">
                <a href="{{ route('print_rekap_harian') }}" target="_blank" ><button class="btn btn-primary mb-3" id="print">Print Rekap Harian</button></a>
              </div>
            <div class="row pb-5" style="position: relative; overflow-x:scroll;">
                <table id="zero_config" class="table table-striped table-bordered no-wrap">
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
                                        <p>{{ \App\Models\Produk::where('id', $item->product_id)->first()->price * $item->qty }}</p>
                                    @endforeach
                                </td>
                                <td>{{ number_format($transaksi->pay_amount, 0, ",", ",") }}</td>
                                <td>{{ ($transaksi->payment->name) ?? 'Belum Bayar' }}</td>
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
        $("#date").on("change", function() {
            $.ajax({
            type: 'GET',
            url: "{{ route('rekap_harian_superadmin_cafe') }}",
            dataType: "JSON",
            data: {
                date : $("#date").val(),
            },
            success: function(data) {
                html = "";
                $.each(data.transactions, function(i, item) {
                    filterData = "";
                    produkData = "";
                    qtyData = "";
                    subTotalData = "";
                    filterData = data.transaction_details.filter(function(res) {
                        return res.transaction_id == item.id;  
                    });

                    $.each(filterData, function(j, produk) {
                        produkData += `
                            <p>${produk.produk_name}</p>
                        `;

                        qtyData += `<p>${produk.qty}</p>`;
                        subTotalData += `<p>${produk.qty * produk.price}</p>`;
                    });
                    
                    html += `
                        <tr>
                            <th scope="row">${i+1}</th>
                            <td>${item.name_customer}</td>
                            <td>${item.user_name}</td>
                            <td>${produkData}</td>
                            <td>${qtyData}</td>
                            <td>${subTotalData}</td>
                            <td>${item.pay_amount}</td>
                            <td>${(item.payment_id == 1) ? "Cash" : (item.payment_id == 2) ? "QRIS" : (item.payment_id == 3) ? "Transfer Bank" : "Belum Bayar"}</td>
                        </tr>
                    `
                });
                $("#humanTime").html(data.human_time);
                $("#pendapatan").html(`Rp. ${data.revenue}`);
                $("#cash").html(`Rp. ${data.earningCash}`);
                $("#qris").html(`Rp. ${data.earningQris}`);
                $("#bank").html(`Rp. ${data.earningBank}`);
                $("#minus").html(`- Rp. ${data.minus}`);
                $("#salah").html(`- Rp. ${data.salah}`);
                $("#report").html(html);   
            }
        });
        });
    });
</script>