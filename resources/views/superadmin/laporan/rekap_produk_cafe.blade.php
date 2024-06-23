@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="mt-5 mx-5">
        <div class="row d-flex justify-content-between">
            <div class="mb-5">
                <h1>Laporan Rekapitulasi Produk Cafe</h1>

                {{-- <h3 id="humanTime">{{ $human_time }}</h3> --}}
            </div>
            <div class="">
                <label for="" class="form-label">Ganti Tanggal</label>
                <input type="date" name="" id="date" class="form-control">
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
            url: "{{ route('rekap_produk_superadmin_cafe') }}",
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