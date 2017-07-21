@extends('layouts.admin')

@section('title', 'Eatvora - Dashboard')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Dashboard'])

        <div class="col-lg-12">
          Hello, This is Admin Dashboard!
        </div>

      </div>
    </div>
  </div>

@endsection
