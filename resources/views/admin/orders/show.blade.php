@extends('layouts.admin')

@section('title', 'Eatvora - Order')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Order of ' . $order->employee->user->name])

        <div class="col-lg-12">
          <div class="no" style="font-size: 22px; font-weight: bold">
            <h2>Order #{{ $order->id }}</h2>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="stats-widget">
            <div class="stats-widget__number">Rp. {{ number_format($order->amount) }}</div>
            <div class="stats-widget__title">Amount</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="stats-widget">
            <div class="stats-widget__number">Rp. {{ number_format($order->vendor_bill) }}</div>
            <div class="stats-widget__title">Vendor Bill</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="stats-widget">
            <div class="stats-widget__number">Rp. {{ number_format($order->revenue) }}</div>
            <div class="stats-widget__title">Revenue</div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="summary">
            <h4>Delivery</h4>
            <div>{{ $order->employee->office->company->name }}</div>
            <div>{{ $order->employee->office->name }}</div>
            <div>{{ $order->delivery_address }}</div>
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-6">

          <div class="meals">
            <h3>Ordered Meals</h3>

            @foreach ($groupedMeals as $date => $meals)
              <h4>{{ date_format(date_create($date), 'l, d M Y') }}</h4>
              <table class="table">
                <thead>
                  <tr>
                    <th>Menu</th>
                    <th style="width: 180px">Vendor</th>
                    <th style="width: 40px">Qty</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($meals as $meal)
                  <tr>
                    <td>{{ $meal->name }}</td>
                    <td>{{ $meal->vendor_name }}</td>
                    <td>{{ $meal->qty }}</td>
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
