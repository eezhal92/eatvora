@extends('layouts.admin-auth')

@section('title', 'Login')

@section('content')
  <div class="container" style="padding: 120px">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4">
        <div class="panel">
          <div class="panel-body">
            <form action="{{ url('/ap/login') }}" method="post">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" class="form-control" type="email" name="email" autofocus="autofocus">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input id="password" class="form-control"  type="password" name="password">
              </div>
              <div class="form-group">
                <button class="btn btn-block btn-primary">
                  Log In
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
