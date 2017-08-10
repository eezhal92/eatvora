@extends('layouts.admin')

@section('title', 'Eatvora - Company List')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Company List'])

        <div class="col-lg-12">
          <div class="action-panel">
            <a href="{{ url('/ap/companies/create') }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New
            </a>
          </div>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Main Office</th>
                <th>Phone</th>
                <th>Email</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($companies as $company)
              <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->mainOffice()->address }}</td>
                <td>+62 21 994 411</td>
                <td>hello@ms.com</td>
                <td>
                  <a href="{{ url('/ap/companies/' . $company->id) }}">View</a>
                  <a href="{{ url('/ap/companies/' . $company->id . '/edit') }}">Edit</a>
                </td>
              </tr>
              @endforeach
          </table>
          {{ $companies->appends(request()->except('page'))->render()}}
        </div>

      </div>
    </div>
  </div>

@endsection
