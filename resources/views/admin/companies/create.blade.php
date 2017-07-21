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

            <div class="form-group">
              <label for="company_name">Name</label>
              <input id="company_name" type="text" name="company_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="company_address">Address</label>
              <textarea name="company_address" id="company_address" class="form-control"></textarea>
            </div>

            <h3 class="form-sub-header">Admin of Company</h3>

            <div class="form-group">
              <label for="admin_name">Name</label>
              <input id="admin_name" type="text" name="admin_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="admin_email">Email</label>
              <input id="admin_email" type="text" name="admin_email" class="form-control">
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
