@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class="row mt-5">
            <div class="col-12 col-lg-8 mt-3"> <!-- Mengubah lebar kolom menjadi 12 pada tampilan mobile -->
                <div data-spy="scroll" style="position: relative; height: 570px; overflow: auto;">
                    <form method="POST" action="{{ route('tambah_pesanan') }}" class="mt-4">
                        @csrf
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $key => $product)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ \App\Models\Produk::find($product->product_id)->name }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            <input type="hidden" name="prev_transaction_detail_id[]"
                                                value="{{ $product->transaction_detail_id }}">
                                            <input type="hidden" name="prev_produk[]" value="{{ $product->product_id }}">
                                            <input type="hidden" name="prev_status[]" value="{{ $product->status }}">
                                            <input type="hidden" name="prev_order_sequence[]"
                                                value="{{ $product->order_sequence }}">
                                            <input type="number" name="prev_qty[]" value="{{ $product->qty }}"
                                                onchange="updateSubtotalTop(this)" data-price="{{ $product->price }}">
                                        </td>
                                        <td class="subtotal">Rp. 0</td>
                                        <td>{{ $product->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 ml-5" style="width: 30%">
                            <label for="search">Cari Menu:</label>
                            <input type="text" id="search" oninput="searchMenu()" class="form-control">
                        </div>

                        <input type="hidden" name="transaksi_id" value="{{ $data->id }}">
                        <table id="zero_confi" class="table table-striped table-bordered no-wrap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Menu</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            <input type="hidden" name="produk[]" value="{{ $product->id }}">
                                            <input type="number" name="qty[]" onchange="updateSubtotalBottom(this)"
                                                data-price="{{ $product->price }}">
                                        </td>
                                        <td class="subtotal">Rp. 0</td>
                                        <td><input type="text" name="pesan[]" id=""></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
            <div class="col-12 col-lg-4 mt-1 px-lg-5"> <!-- Mengubah lebar kolom menjadi 12 pada tampilan mobile -->
                <div class="">
                    <div class="form-group">
                        <label for="name">Nama Customer</label>
                        <input type="text" name="name" class="form-control border-primary"
                            value="{{ $data->name_customer }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nomor Meja</label>
                        <select name="table_id" id="table_id" class="js-select2 form-control border-primary"
                            style="width: 100%;   height: calc(1.5em + 0.75rem + 2px);" required>
                            <option value="{{ $data->table_id }}" selected>{{ ($data->table->number) ?? "" }}</option>
                            @foreach ($tables as $table)
                                <option value="{{ $table->id }}">
                                    {{ $table->number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Dibungkus/Ditempat</label>
                        <select name="order_type" id="order_type" class="form-control border-primary" required>
                            <option value="" disabled selected>Pilih</option>
                            <option value="dibungkus">Dibungkus</option>
                            <option value="ditempat">Ditempat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Total Price</label>
                        <input type="number" name="price_amount" class="form-control border-primary" value="0"
                            readonly>
                    </div>
                    <div class="row py-2">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-lg btn-success btn-block" data-toggle="modal"
                                data-target="#confirmOrderCenter">
                                Tambah Order
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var subtotalValuesTop = [];
        var subtotalValuesBottom = [];

        function updateSubtotalTop(input) {
            var price = parseFloat(input.getAttribute('data-price'));
            var quantity = parseInt(input.value);

            if (!isNaN(quantity) && quantity >= 0) {
                var subtotal = price * quantity;
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValuesTop[index] = subtotal;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. ' + subtotal.toLocaleString();

                updateTotalPrice();
                console.log(subtotalValuesTop);
            } else {
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValuesTop[index] = 0;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. 0';
                updateTotalPrice();
            }
        }

        function updateSubtotalBottom(input) {
            var price = parseFloat(input.getAttribute('data-price'));
            var quantity = parseInt(input.value);

            if (!isNaN(quantity) && quantity >= 0) {
                var subtotal = price * quantity;
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValuesBottom[index] = subtotal;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. ' + subtotal.toLocaleString();

                updateTotalPrice();
                console.log(subtotalValuesBottom);
            } else {
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValuesBottom[index] = 0;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. 0';
                updateTotalPrice();
            }
        }

        function updateTotalPrice() {
            var totalPriceTop = subtotalValuesTop.reduce(function(accumulator, currentValue) {
                return accumulator + currentValue;
            }, 0);

            var totalPriceBottom = subtotalValuesBottom.reduce(function(accumulator, currentValue) {
                return accumulator + currentValue;
            }, 0);

            var totalPrice = totalPriceTop + totalPriceBottom;

            var totalPriceInput = document.getElementsByName('price_amount')[0];
            totalPriceInput.value = totalPrice;
        }

        function updateReturn() {
            var totalPrice = parseFloat(document.getElementsByName('price_amount')[0].value);
            var paid = parseFloat(document.getElementsByName('paid')[0].value);

            var returnInput = document.getElementsByName('return')[0];
            returnInput.value = (paid - totalPrice).toFixed(2);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var inputProdukTop = document.querySelectorAll('input[name="prev_qty[]"]');
            inputProdukTop.forEach(function(input) {
                updateSubtotalTop(input);
            });

            var inputProdukBottom = document.querySelectorAll('input[name="qty[]"]');
            inputProdukBottom.forEach(function(input) {
                updateSubtotalBottom(input);
            });

            updateTotalPrice(); // Memanggil fungsi updateTotalPrice setelah kedua tabel selesai dimuat

        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>

    <script>
        function searchMenu() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("zero_confi");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                // Ubah angka 1 sesuai dengan indeks kolom yang berisi nama menu
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;

                    // Ubah kedua nilai menjadi huruf besar untuk mencocokkan secara case-insensitive
                    txtValue = txtValue.toUpperCase();
                    filter = filter.toUpperCase();

                    // Menggunakan metode includes() untuk mencocokkan keyword
                    if (txtValue.includes(filter)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }

            // Memanggil ulang fungsi updateTotalPrice setelah melakukan pencarian
            updateTotalPriceTop();
            updateTotalPriceBottom();
        }
    </script>
    <script>
        window.onbeforeunload = function() {
            return "Anda yakin ingin meninggalkan halaman? Perubahan yang belum disimpan akan hilang.";
        }
    </script>
@endsection