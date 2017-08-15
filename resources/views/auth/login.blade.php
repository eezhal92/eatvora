@extends('layouts.employee-auth')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4" style="padding-top: 120px">
        <div class="panel account-panel">

          <div class="panel-body account-panel__body">

            <form role="form" method="POST" action="{{ route('login') }}">
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

              <div class="form-group {{ $errors->first('password', 'has-error') }}">
                  <label for="password">Password</label>
                  <input id="password" type="password" class="form-control" name="password" required>
                  @if ($errors->has('password'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                  </label>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn--primary btn-block">
                  Masuk
                </button>
              </div>

            </form>

          </div>

          <div class="panel-footer account-panel__footer">
            <a href="{{ route('password.request') }}">
              Lupa Password?
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
