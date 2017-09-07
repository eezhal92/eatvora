@extends('layouts.employee-auth')

@section('title', 'Reset Password - Eatvora')

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

            <form role="form" method="POST" action="{{ route('password.request') }}">
              {{ csrf_field() }}

              <input type="hidden" name="token" value="{{ $token }}">

              <div class="form-group {{ $errors->first('email', 'has-error') }}">
                  <label for="email">E-Mail</label>
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

              <div class="form-group{{ $errors->first('password_confirmation', ' has-error') }}">
                <label for="password_confirmation">Konfirmasi Password</label>
                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                  @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                  @endif
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn--primary btn-block">
                  Reset Password
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

