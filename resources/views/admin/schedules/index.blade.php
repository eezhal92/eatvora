@extends('layouts.admin')

@section('title', 'Eatvora - Schedule')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Meal Schedule'])

        <div class="col-lg-12">
          <div class="action-panel">
            <a href="{{ url('/ap/schedules/create') }}" class="btn btn-primary">Create</a>
          </div>
          <div class="action-panel">
            <form class="form-inline" action="{{ url('/ap/orders') }}">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">From</span>
                  <input id="date-from" class="form-control" type="date" name="date_from" value="{{ $dateRange[0] }}">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">To</span>
                  <input id="date-to" class="form-control" type="date" name="date_to" value="{{ $dateRange[1] }}">
                </div>
              </div>
              <a href="{{ $prevLink }}" class="btn btn-default">Prev</a>
              <a href="{{ $nextLink }}" class="btn btn-default">Next</a>
              <button class="pull-right btn btn-default" type="submit" style="margin-left: 5px">Search</button>
              <a href="{{ url('/ap/payments') }}" class="pull-right btn btn-default" type="submit">Reset</a>
            </form>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="meal-group">
            @if (!$mealCount)
            <p>No meal scheduled</p>
            @endif
            @foreach($mealGroups as $date => $meals)
            <h3>{{ date_format(date_create($date), 'l, d M Y') }}</h3>
            <br>
            <table class="table">
              <thead>
                <tr>
                  <th style="width: 400px">Name</th>
                  <th>Vendor</th>
                  <th style="width: 80px">Qty</th>
                  <th style="width: 80px"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($meals as $meal)
                  <tr>
                    <td>{{ $meal->name }}</td>
                    <td>{{ $meal->vendor->name }}</td>
                    <td>{{ $meal->qty }}</td>
                    <td><a href="#">Remove</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @endforeach
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
