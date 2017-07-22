@extends('layouts.admin')

@section('title', 'Eatvora - Edit Office ' . $office->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Edit ' . $office->name])

        <div class="col-lg-6">
          <form action="{{ url("/ap/companies/{$company->id}/offices/{$office->id}") }}" method="post">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $office->name) }}">
            </div>

            <div class="form-group">
              <label for="address">Address</label>
              <textarea id="address" name="address" class="form-control">{{ old('address', $office->address) }}</textarea>
            </div>

            <div class="form-group">
              <label for="phone">Phone</label>
              <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', $office->phone) }}">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $office->email) }}">
            </div>

            <button class="btn btn-primary">
              Update Office
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
