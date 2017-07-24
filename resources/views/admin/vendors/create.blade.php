@extends('layouts.admin')

@section('title', 'Eatvora - Create Vendor')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Create New Vendor'])

        <div class="col-lg-6">
          <form action="{{ url("/ap/vendors") }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control" value="{{ old('name')}}">
            </div>

            <div class="form-group">
              <label for="capacity">Capacity</label>
              <input id="capacity" type="number" min="10" name="capacity" class="form-control" value="{{ old('capacity')}}">
            </div>

            <div class="form-group">
              <label for="address">Address</label>
              <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
            </div>

            <div class="form-group">
              <label for="phone">Phone</label>
              <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone')}}">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="text" name="email" class="form-control" value="{{ old('email')}}">
            </div>

            <button class="btn btn-primary">
              Create New Vendor
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
