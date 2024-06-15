@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        <div class="login-form">
            <form action="{{ route('account.authenticate') }}" method="post">
                @csrf
                <h4 class="modal-title">Login ke Akun Anda</h4>
                <div class="form-group">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group small">
                    <a href="{{ route('front.forgotPassword') }}" class="forgot-link">Lupa Password?</a>
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
                <div class="form-group mt-3">
                    <a href="{{ route('redirect') }}" class="btn btn-google w-100 btn-lg">
                        <i class="fa fa-google"></i> Login dengan Google
                    </a>
                </div>
            </form>
            <div class="text-center small">Belum mempunyai akun? <a href="{{ route('account.register') }}">Register</a></div>
        </div>
    </div>
</section>
@endsection