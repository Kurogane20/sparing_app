@extends('adminlte.layouts.auth')

@section('content')
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="card card-outline">
        <div class="card-header text-center">
          <div class="login-logo">
            <img src="{{ asset('Logo.png') }}" alt="Sparing App Logo">
          </div>
          <p class="mt-2 mb-0" style="color:rgba(255,255,255,0.8);font-size:0.82rem;font-weight:500;letter-spacing:0.5px;">SPARING APP</p>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masuk untuk melanjutkan sesi</p>
          <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Alamat Email" autofocus>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope" style="color:#94a3b8;"></span>
                </div>
              </div>
              @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock" style="color:#94a3b8;"></span>
                </div>
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="row align-items-center mb-3">
              <div class="col-7">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label for="remember">Ingat saya</label>
                </div>
              </div>
              <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                </button>
              </div>
            </div>
          </form>

          <div style="border-top:1px solid #f1f5f9;margin-top:8px;padding-top:14px;text-align:center;">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="display:block;margin-bottom:6px;">
              <i class="fas fa-key mr-1"></i> {{ __('Lupa Password?') }}
            </a>
            @endif
            @if (Route::has('register'))
            <a href="{{ route('register') }}">
              <i class="fas fa-user-plus mr-1"></i> {{ __('Buat Akun Baru') }}
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
@endsection
