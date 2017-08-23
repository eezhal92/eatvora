@extends('layouts.employee')

@section('title', 'Profile')

@section('body')
<div class="container container--small">

  <div class="row">
    <div class="col-xs-12">
      <div class="page-title-greeting" style="margin-bottom: 28px">
        <h1 class="page-title"></h1>
      </div>
    </div>
  </div>

  @include('message')

  <div class="row">
    <div class="col-xs-12 col-sm-3">
      <ul class="stacked-nav">
        <li role="presentation"><a href="{{ url('/profile') }}">Edit Profil</a></li>
        <li role="presentation"><a class="active" href="{{ url('/change-password') }}">Ganti Password</a></li>
      </ul>
    </div>

    <div class="col-xs-12 col-sm-offset-1 col-sm-8">
      <div class="panel account-panel" style="padding: 20px 40px 0">
        <div class="panel-body">
          <form action="{{ route('change-password.store') }}" class="form-horizontal" method="post">
            {{ csrf_field() }}
            <div class="form-group {{ $errors->first('old_password', 'has-error') }}">
              <label for="name" class="col-sm-4 control-label">Password Lama</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="old_password">
                @if ($errors->has('old_password'))
                  <span class="help-block">{{ $errors->first('old_password') }}</span>
                @endif
              </div>
            </div>

            <div class="form-group {{ $errors->first('new_password', 'has-error') }}">
              <label for="name" class="col-sm-4 control-label">Password Baru</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="new_password">
                @if ($errors->has('new_password'))
                  <span class="help-block">{{ $errors->first('new_password') }}</span>
                @endif
              </div>
            </div>

            <div class="form-group {{ $errors->first('new_password_confirmation', 'has-error') }}">
              <label for="name" class="col-sm-4 control-label">Konfirmasi Password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="new_password_confirmation">
                @if ($errors->has('new_password_confirmation'))
                  <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn--primary">Simpan</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

