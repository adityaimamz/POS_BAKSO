@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <div class="row mt-5">
            <div class="col-12 col-lg-8 mt-3">
                <div data-spy="scroll" style="position: relative; height: 570px; overflow: auto;">
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Menu</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product as $key => $product)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ \App\Models\Produk::find($product->product_id)->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td><input type="number" name="product[]" value="{{ $product->qty }}"
                                            onchange="updateSubtotal(this)" data-price="{{ $product->price }}" readonly>
                                    </td>
                                    <td class="subtotal">Rp. 0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-lg-4 mt-1 px-lg-5">
                <form action="{{ route('selesaikan_pesanan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $data->id }}">
                    <div class="mr-5">
                        <div class="form-group">
                            <label for="name">Nama Customer</label>
                            <input type="text" name="name_customer" value="{{ $data->name_customer }}"
                                class="form-control border-primary" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Jumlah Pesanan</label>
                            <input type="number" name="total_item" class="form-control border-primary"
                                value="{{ $data->transaction_detail->sum('qty') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="price">Total Harga</label>
                            <input type="number" name="price_amount" class="form-control border-primary"
                                value="{{ $data->price_amount }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="price">Dibayar</label>
                            <div class="input-group">
                                <div class="mb-2">
                                    <input type="number" name="paid" id="paid" class="form-control border-primary" value=""
                                        onchange="updateReturn()">
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="addAmount(50000)">50000</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="addAmount(100000)">100000</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Kembalian</label>
                            <input type="text" name="return" class="form-control border-primary" value="0"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Metode Pembayaran</label>
                            <select name="payment_id" id="payment_id" class="form-control border-primary">
                                <option value="" readonly selected>Pilih Metode Pembayaran</option>
                                @foreach ($payment as $pay)
                                    <option value="{{ $pay->id }}">
                                        {{ $pay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row py-2">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg btn-success btn-block" data-toggle="modal"
                                    data-target="#confirmOrderCenter" disabled id="btn-selesai">
                                    Selesaikan Orderan
                                </button>
                            </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        function updateSubtotal(input) {
            var price = parseFloat(input.getAttribute('data-price'));
            var quantity = parseInt(input.value);
            var subtotal = price * quantity;

            // Menemukan elemen td.subtotal terkait dan mengupdate nilainya
            var subtotalElement = input.parentNode.nextElementSibling;
            subtotalElement.innerHTML = 'Rp. ' + subtotal.toLocaleString();
        }

        function addAmount(amount) {
            var paidInput = document.getElementById('paid');
            var currentPaid = parseFloat(paidInput.value) || 0;
            paidInput.value = currentPaid + amount;
            updateReturn(); // Memanggil fungsi updateReturn setelah menambah nilai
        }

        // function updateTotalPrice() {
        //     var subtotalElements = document.getElementsByClassName('subtotal');
        //     var totalPrice = 0;

        updateReturn(); // Memanggil fungsi updateReturn setelah menambah nilai
    

        function updateReturn() {
            var totalPrice = parseFloat(document.getElementsByName('price_amount')[0].value);
            var paid = parseFloat(document.getElementsByName('paid')[0].value);
            var returnInput = document.getElementsByName('return')[0];

            var returnAmount = (paid - totalPrice).toFixed(
                2); // Menggunakan toFixed(2) untuk membatasi desimal menjadi dua digit
            returnInput.value = 'Rp. ' + returnAmount.replace(/\d(?=(\d{3})+\.)/g, '$&,'); // Menambahkan format Rupiah
        }


        document.addEventListener("DOMContentLoaded", function() {
            var inputProduk = document.querySelectorAll('input[name="product[]"]');
            inputProduk.forEach(function(input) {
                updateSubtotal(input);
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
          $(document).ready(function() {
        // Menangkap perubahan pada input #paid dan select #payment_id
        $('#paid, #payment_id').on('change input', function() {
            var paid = $('#paid').val();
            var paymentId = $('#payment_id').val();

            let btn_selesai = document.getElementById("btn-selesai");

            // Memeriksa apakah nilai paid dan payment_id tidak kosong
            if (paid && paymentId) {
                btn_selesai.disabled = false;
            } else {
                btn_selesai.disabled = true;
            }
        });
    });
    </script>
@endsection
