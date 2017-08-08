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

            <div class="form-group {{ $errors->first('name', 'has-error') }}">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $office->name) }}">
              @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('address', 'has-error') }}">
              <label for="address">Address</label>
              <textarea id="address" name="address" class="form-control">{{ old('address', $office->address) }}</textarea>
              @if ($errors->has('address'))
                <span class="help-block">{{ $errors->first('address') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('phone', 'has-error') }}">
              <label for="phone">Phone</label>
              <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', $office->phone) }}">
              @if ($errors->has('phone'))
                <span class="help-block">{{ $errors->first('phone') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('email', 'has-error') }}">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $office->email) }}">
              @if ($errors->has('email'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
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
