@extends('layouts.admin')

@section('title', 'Eatvora - Create New Company')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Create New Company'])

        <div class="col-lg-6">
          <form action="{{ url('/ap/companies') }}" method="post">
            {{ csrf_field() }}

            <h3 class="form-sub-header">Company</h3>

            <div class="form-group {{ $errors->first('company_name', 'has-error') }}">
              <label for="company_name">Name</label>
              <input id="company_name" type="text" name="company_name" class="form-control">
              @if ($errors->has('company_name'))
                <span class="help-block">{{ $errors->first('company_name') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('company_address', 'has-error') }}">
              <label for="company_address">Address</label>
              <textarea name="company_address" id="company_address" class="form-control"></textarea>
              @if ($errors->has('company_address'))
                <span class="help-block">{{ $errors->first('company_address') }}</span>
              @endif
            </div>

            <h3 class="form-sub-header">Admin of Company</h3>

            <div class="form-group {{ $errors->first('admin_name', 'has-error') }}">
              <label for="admin_name">Name</label>
              <input id="admin_name" type="text" name="admin_name" class="form-control">
              @if ($errors->has('admin_name'))
                <span class="help-block">{{ $errors->first('admin_name') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('admin_email', 'has-error') }}">
              <label for="admin_email">Email</label>
              <input id="admin_email" type="text" name="admin_email" class="form-control">
              @if ($errors->has('admin_email'))
                <span class="help-block">{{ $errors->first('admin_email') }}</span>
              @endif
            </div>

            <button class="btn btn-primary">
              Create New Company
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
