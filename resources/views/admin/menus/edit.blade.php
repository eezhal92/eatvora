@extends('layouts.admin')

@section('title', 'Eatvora - Edit ' . $menu->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Edit ' . $menu->name])

        <div class="col-lg-6">
          <form action="{{ url("/ap/menus/" . $menu->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="form-group {{ $errors->first('name', 'has-error') }}">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $menu->name)}}">
              @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('price', 'has-error') }}">
              <label for="price">Price</label>
              <input id="price" type="number" min="10" name="price" class="form-control" value="{{ old('price', $menu->price)}}">
              @if ($errors->has('price'))
                <span class="help-block">{{ $errors->first('price') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('description', 'has-error') }}">
              <label for="description">Description</label>
              <textarea name="description" id="description" class="form-control">{{ old('description', $menu->address) }}</textarea>
              @if ($errors->has('description'))
                <span class="help-block">{{ $errors->first('description') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('contents', 'has-error') }}">
              <label for="contents">Contents</label>
              <textarea name="contents" id="contents" class="form-control">{{ old('contents', $menu->contents) }}</textarea>
              @if ($errors->has('contents'))
                <span class="help-block">{{ $errors->first('contents') }}</span>
              @endif
            </div>

            <button class="btn btn-primary">
              Update Menu
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
