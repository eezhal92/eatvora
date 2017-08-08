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

            <div class="form-group {{ $errors->first('name', 'has-error') }}">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control" value="{{ old('name')}}">
              @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('address', 'has-error') }}">
              <label for="address">Address</label>
              <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
              @if ($errors->has('address'))
                <span class="help-block">{{ $errors->first('address') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('phone', 'has-error') }}">
              <label for="phone">Phone</label>
              <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone')}}">
              @if ($errors->has('phone'))
                <span class="help-block">{{ $errors->first('phone') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('email', 'has-error') }}">
              <label for="email">Email</label>
              <input id="email" type="text" name="email" class="form-control" value="{{ old('email')}}">
              @if ($errors->has('email'))
                <span class="help-block">{{ $errors->first('email') }}</span>
              @endif
            </div>

            <button class="btn btn-primary">
              Create New Office
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
