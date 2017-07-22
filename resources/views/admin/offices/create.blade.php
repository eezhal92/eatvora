@extends('layouts.admin')

@section('title', 'Eatvora - Create Office For ' . $company->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Create Office For ' . $company->name])

        <div class="col-lg-6">
          <form action="{{ url("/ap/companies/{$company->id}/offices") }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control">
            </div>

            <div class="form-group">
              <label for="address">Address</label>
              <textarea name="address" id="address" class="form-control"></textarea>
            </div>

            <div class="form-group">
              <label for="phone">Phone</label>
              <input id="phone" type="text" name="phone" class="form-control">
            </div>

            <div class="form-group">
              <label for="phone">Email</label>
              <input id="email" type="text" name="phone" class="form-control">
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
