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

            <div class="form-group {{ $errors->first('name', 'has-error') }}">
              <label for="name">Name</label>
              <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $company->name) }}">
              @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <h3 class="form-sub-header">Main Office</h3>

            <div class="form-group {{ $errors->first('main_office_name', 'has-error') }}">
              <label for="main_office_name">Name</label>
              <input id="main_office_name" name="main_office_name" class="form-control" type="text" value="{{ old('main_office_name', $mainOffice->name) }}">
              @if ($errors->has('main_office_name'))
                <span class="help-block">{{ $errors->first('main_office_name') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label for="main_office_address {{ $errors->first('main_office_address', 'has-error') }}">Address</label>
              <textarea id="main_office_address" name="main_office_address" class="form-control" type="text">{{ old('main_office_address', $mainOffice->address) }}</textarea>
            </div>

            <div class="form-group">
              <label for="main_office_email {{ $errors->first('main_office_email', 'has-error') }}">Email</label>
              <input id="main_office_email" name="main_office_email" class="form-control" type="text" value="{{ old('main_office_email', $mainOffice->email) }}">
              @if ($errors->has('main_office_email'))
                <span class="help-block">{{ $errors->first('main_office_email') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label for="main_office_phone {{ $errors->first('main_office_phone', 'has-error') }}">Phone</label>
              <input id="main_office_phone" name="main_office_phone" class="form-control" type="text" value="{{ old('main_office_phone', $mainOffice->phone) }}">
              @if ($errors->has('main_office_phone'))
                <span class="help-block">{{ $errors->first('main_office_phone') }}</span>
              @endif
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
