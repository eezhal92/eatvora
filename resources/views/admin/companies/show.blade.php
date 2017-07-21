@extends('layouts.admin')

@section('title', 'Eatvora - ' . $company->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $company->name])

        <div class="col-lg-12">
          <div class="offices">
            @foreach($company->offices as $office)
              <div class="office-item">
                <h2>{{ $office->name }}</h2>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
