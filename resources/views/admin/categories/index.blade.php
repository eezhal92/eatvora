@extends('layouts.admin')

@section('title', 'Eatvora - Categories')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Meal Categories'])

        <div class="col-lg-4">
          <h4>Create New Category</h4>
          <form action="{{ url('/ap/categories') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>
            <button class="btn btn-primary">Save</button>
          </form>
        </div>

        <div class="col-lg-offset-1 col-lg-7">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Slug</th>
                <th style="width: 100px"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $category)
                <tr>
                  <td>{{ $category->name }}</td>
                  <td>{{ $category->slug }}</td>
                  <td>
                    <a href="{{ url(sprintf('/ap/categories/%s/edit', $category->id)) }}">Edit</a>
                    <a href="#" data-id="{{ $category->id }}" class="btn-delete">Delete</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {!! $categories->appends(request()->except('page'))->render() !!}
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
  <script>
    'use strict';

    $('.btn-delete').on('click', function () {
      const id = $(this).data('id');

      const confirmed = confirm('Are you sure want to delete this?');

      if (!confirmed) {
        return;
      }

      axios.delete(`/ap/categories/${id}`)
        .then(() => {
          alert('Category has been deleted');

          window.location.reload();
        })
        .catch(err => {
          alert('Cannot delete the category');
          throw err;
        });
    });
  </script>
@endpush
