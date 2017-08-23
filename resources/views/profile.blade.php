@extends('layouts.employee')

@section('title', 'Profile')

@section('body')
<div class="container container--small">

  <div class="row">
    <div class="col-xs-12">
      <div class="page-title-greeting" style="margin-bottom: 28px">
        <h1 class="page-title">

        </h1>
      </div>
    </div>
  </div>

  @include('message')

  <div class="row">
    <div class="col-xs-12 col-sm-3">
      <ul class="stacked-nav">
        <li role="presentation"><a class="active" href="{{ url('/profile') }}">Edit Profil</a></li>
        <li role="presentation"><a href="{{ url('/change-password') }}">Ganti Password</a></li>
      </ul>
    </div>

    <div class="col-xs-12 col-sm-offset-1 col-sm-8">
      <div class="panel account-panel" style="padding: 20px 40px 0">
        <div class="panel-body">
          <form action="{{ route('profile.store') }}" class="form-horizontal" method="post">
            {{ csrf_field() }}
            <div class="form-group {{ $errors->first('name') }}">
              <label for="name" class="col-sm-2 control-label">Nama</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
              </div>
            </div>
            <div class="form-group {{ $errors->first('email') }}">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
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

