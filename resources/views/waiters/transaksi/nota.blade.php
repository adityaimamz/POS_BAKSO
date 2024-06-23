<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 15pt;
        }
        table td {
            font-size: 12pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="generatePDF()">
    {{-- <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button> --}}
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">Bakso Lik Tono</h3>
        <p>{{ strtoupper($location->locations) }}</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    {{-- <p>No: {{ ($data->id) }}</p> --}}
    <p class="text-center">===================================</p>
    <div class="">
        <p>
            Nama : {{ $product[0]->name_customer }} 
        </p>
        <p>
            Meja : {{ ($product[0]->table->number) ?? "" }}
        </p>
    </div>
    <p class="text-center">===================================</p>
    <br>
    <table width="100%" style="border: 0;">
        @foreach ($product as $item)
            <tr>
                <td colspan="3">{{ \App\Models\Produk::where('id', $item->product_id)->first()->name }}</td>
            </tr>
            {{-- <tr>
                <td colspan="3">{{ ($item->table->number) ?? "" }}</td>
            </tr> --}}
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->price, '0', ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->qty * $item->price, '0', ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">{{ number_format($data->price_amount, '0', ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Item:</td>
            <td class="text-right">{{ number_format($product->sum('qty'), '0', ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon:</td>
            <td class="text-right">{{ ($data->discount) }}</td>
        </tr>
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">{{ number_format($data->price_amount, '0', ',', '.') }}</td>
        </tr>
        <tr>
            <td>Jumlah Bayar:</td>
            <td class="text-right">{{ number_format($product[0]->pay_receive, '0', ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">{{ $product[0]->pay_return  }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js"></script>
       <script>
    function generatePDF() {
        const element = document.body; // Ganti dengan elemen yang ingin Anda konversi ke PDF

        html2pdf(element, {
            margin: 10,
            filename: 'nota-<?=$data->id?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a6', orientation: 'portrait' }
        });

        // Menambahkan penundaan 3 detik sebelum mengalihkan
        setTimeout(function () {
            window.location.href = "{{ route('transaksi.selesai') }}";
        }, 5000);
    }
</script>

</body>
</html>