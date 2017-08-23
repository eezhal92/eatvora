@extends('layouts.admin')

@section('title', 'Eatvora - Balance Log of ' . $user->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Balance Log of ' . $user->name])

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              @foreach($balances as $balance)
                <tr>
                  <td>{{ $balance->created_at->format('d M Y H:i:s') }}</td>
                  <td>{{ $balance->formattedAmount() }}</td>
                  <td>{{ $balance->type }}</td>
                  <td>{{ $balance->description }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-center">
              {{ $balances->appends(request()->except('page'))->render() }}
            </div>
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
