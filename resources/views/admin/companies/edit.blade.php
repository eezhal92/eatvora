@extends('layouts.admin')

@section('title', 'Eatvora - Edit ' . $company->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => "Edit {$company->name}"])

        <div class="col-lg-6">
          <form action="{{ url("/ap/companies/{$company->id}") }}" method="post">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $company->name) }}">
            </div>

            <h3 class="form-sub-header">Main Office</h3>

            <div class="form-group">
              <label for="main_office_name">Name</label>
              <input id="main_office_name" name="main_office_name" class="form-control" type="text" value="{{ old('main_office_name', $mainOffice->name) }}">
            </div>

            <div class="form-group">
              <label for="main_office_address">Address</label>
              <textarea id="main_office_address" name="main_office_address" class="form-control" type="text">{{ old('main_office_address', $mainOffice->address) }}</textarea>
            </div>

            <div class="form-group">
              <label for="main_office_email">Email</label>
              <input id="main_office_email" name="main_office_email" class="form-control" type="text" value="{{ old('main_office_email', $mainOffice->email) }}">
            </div>

            <div class="form-group">
              <label for="main_office_phone">Phone</label>
              <input id="main_office_phone" name="main_office_phone" class="form-control" type="text" value="{{ old('main_office_phone', $mainOffice->phone) }}">
            </div>

            <button class="btn btn-primary">
              Update Company
            </button>
          </form>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
