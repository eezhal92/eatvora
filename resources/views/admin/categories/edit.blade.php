@extends('layouts.admin')

@section('title', 'Eatvora - Categories')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Meal Categories'])

        <div class="col-lg-4">
          <h4>Edit Category</h4>
          <form action="{{ url('/ap/categories/' . $category->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}">
            </div>
            <button class="btn btn-primary">Save</button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
@endpush
