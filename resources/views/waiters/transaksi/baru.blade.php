@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class=" mt-5">
            <div class="col-12 col-lg-12 mt-3"> <!-- Mengubah lebar kolom menjadi 12 pada tampilan mobile -->
                <div data-spy="scroll" style="position: relative; height: 570px; overflow: auto;">
                    <div class="mt-3 ml-5" style="width: 30%">
                        <label for="search">Cari Menu:</label>
                        <input type="text" id="search" oninput="searchMenu()" class="form-control">
                    </div>
                    <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data"
                        class="mt-4">
                        @csrf
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
                                            <input type="number" name="qty[]" onchange="updateSubtotal(this)"
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
            <div class="col-12 col-lg-12 mt-1 pt-5 px-lg-5"> <!-- Mengubah lebar kolom menjadi 12 pada tampilan mobile -->
                <div class="">
                    <div class="form-group">
                        <label for="name">Nama Customer</label>
                        <input type="text" name="name_customer" id="nama_customer" class="form-control border-primary"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nomor Meja</label>
                        <select name="table_id" id="table_id" class="js-select2 form-control border-primary" readonly >
                            <option value="" disabled selected>Pilih Nomor Meja</option>
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
                        <label for="total_price">Total Harga</label>
                        <input type="text" name="price_amount" class="form-control border-primary" value="0"
                            readonly>
                    </div>
                    <div class="row py-2">
                        <div class="col-12 col-md-12 text-center">
                            <!-- Mengubah lebar kolom menjadi 12 pada tampilan mobile -->
                            <button type="submit" id="btn-selesai" class="btn btn-lg btn-success btn-block"
                                data-toggle="modal" data-target="#confirmOrderCenter">
                                Buat Transaksi
                            </button>
                        </div>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
    <script>
        var subtotalValues = [];

        function updateSubtotal(input) {
            var price = parseFloat(input.getAttribute('data-price'));
            var quantity = parseInt(input.value);

            if (!isNaN(quantity) && quantity >= 0) {
                var subtotal = price * quantity;
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValues[index] = subtotal;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. ' + subtotal.toLocaleString();

                updateTotalPrice();
            } else {
                var index = parseInt(input.parentNode.parentNode.rowIndex) - 1;
                subtotalValues[index] = 0;

                var subtotalElement = input.parentNode.nextElementSibling;
                subtotalElement.innerHTML = 'Rp. 0';
                updateTotalPrice();
            }
        }

        function updateTotalPrice() {
            var totalPrice = subtotalValues.reduce(function(accumulator, currentValue) {
                return accumulator + currentValue;
            }, 0);

            var totalPriceInput = document.getElementsByName('price_amount')[0];
            totalPriceInput.value = totalPrice;
        }
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
            updateTotalPrice();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        window.onbeforeunload = function() {
            return "Anda yakin ingin meninggalkan halaman? Perubahan yang belum disimpan akan hilang.";
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>


    <script>
        $('#nama_customer, #table_id').on('input', function() {
            var customerName = $('#nama_customer').val();
            var tableId = $('#table_id').val();
            var btnSelesai = document.getElementById("btn-selesai");

            if (customerName) {
                btnSelesai.disabled = false;
            } else {
                btnSelesai.disabled = true;
            }
        });
    </script>
@endsection
