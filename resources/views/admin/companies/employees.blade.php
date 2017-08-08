@extends('layouts.admin')

@section('title', sprintf('Eatvora - %s Employee List', $company->name))

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => sprintf('Eatvora - %s Employee List', $company->name)])

        <div class="col-lg-12">
          <div class="action-panel" style="margin-bottom: 15px;">
            <a href="{{ url('/ap/companies/create') }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <form action="" class="form-inline" style="padding: 15px; border-radius: 3px; margin: 20px 0 25px; background: #f3f3f3">
            <div class="form-group">
              <label for="query">Query</label>
              <input type="text" id="query" name="query" class="form-control" value="{{ request('query') }}" placeholder="Search by name or email..." {{ request('query') ? 'autofocus' : '' }}>
            </div>
            <div class="form-group">
              <label for="active">Active Status</label>
              <select name="active" id="active" class="form-control">
                <option value="" {{ request('active') === '' ? 'selected' : '' }}>All</option>
                <option value="true" {{ request('active') === 'true' ? 'selected' : '' }}>Yes</option>
                <option value="false" {{ request('active') === 'false' ? 'selected' : '' }}>No</option>
              </select>
            </div>
            <button type="submit" class="pull-right btn btn-default">
              Search
            </button>
          </form>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Office</th>
                <th>Active?</th>
                <th>Created At</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($employees as $employee)
              <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->office_name }}</td>
                <td>{{ $employee->active ? 'Yes' : 'No' }}</td>
                <td>{{ $employee->created_at->format('d M Y, H:i') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $employees->appends(request()->except('page'))->render()}}
        </div>

      </div>
    </div>
  </div>

@endsection
