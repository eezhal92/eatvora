@extends('layouts.admin')

@section('title', sprintf('Eatvora - %s Employee List', $company->name))

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => sprintf('Eatvora - %s Employee List', $company->name)])

        <div class="col-lg-12">
          <div class="action-panel">
            <a href="{{ url('/ap/companies/create') }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="action-panel">
            <form class="form-inline">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">Query</span>
                  <input type="text" id="query" name="query" class="form-control" value="{{ request('query') }}" placeholder="Search by name or email..." {{ request('query') ? 'autofocus' : '' }}>
                </div>
              </div>
              <div class="form-group">
                <select name="active" id="active" class="form-control">
                  <option value="" {{ request('active') === '' ? 'selected' : '' }}>All Status</option>
                  <option value="true" {{ request('active') === 'true' ? 'selected' : '' }}>Active</option>
                  <option value="false" {{ request('active') === 'false' ? 'selected' : '' }}>Not Active</option>
                </select>
              </div>
              <button type="submit" class="pull-right btn btn-default">
                Search
              </button>
            </form>
          </div>
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
                <td><a href="{{ url(sprintf('/ap/users/%s/balances', $employee->user_id)) }}">Balance Log</a></td>
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
