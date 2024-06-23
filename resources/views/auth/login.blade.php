@extends('layouts.auth')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
style="background:url(assets/images/big/auth-bg.jpg) no-repeat center center;">
<div class="auth-box row">
    <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(assets/images/bakso.jpg);">
    </div>
    <div class="col-lg-5 col-md-7 bg-white">
        <div class="p-3">
            <div class="text-center">
                <img src="assets/images/bakso-icon.png" style="height: 150px" alt="wrapkit">
            </div>
            <h2 class="mt-1 text-center">Bakso Liktono</h2>
            <p class="text-center">Silahkan Masukan Email dan Password.</p>
            @if (Session::has('login'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ Session::get('login') }}
            </div>
            @endif
            <form method="POST" action="{{ route('login') }}" novalidate class="mt-4">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="text-dark" for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email"
                                placeholder="masukan email">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="text-dark" for="password">Password</label>
                            <input class="form-control" id="password" name="password" type="password"
                                placeholder="masukan password">
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-block btn-dark">Masuk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection