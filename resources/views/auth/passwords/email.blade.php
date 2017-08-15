@extends('layouts.employee-auth')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4" style="padding-top: 120px">
        <div class="panel account-panel">

          <div class="panel-body account-panel__body">
            @if (session('status'))
              <div class="alert alert-success">
                {{ session('status') }}
              </div>
            @endif

            <form role="form" method="POST" action="{{ route('password.email') }}">
              {{ csrf_field() }}

              <div class="form-group {{ $errors->first('email', 'has-error') }}">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn--primary btn-block">
                  Kirim Tautan Reset Password
                </button>
              </div>

            </form>

          </div>
          <div class="panel-footer account-panel__footer">
            <a href="{{ url('/login') }}">
              Kembali ke Halaman Login
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


