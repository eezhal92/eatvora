@extends('layouts.admin')

@section('title', 'Eatvora - Order')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Order of ' . $order->employee->user->name])

        <div class="col-lg-5">
          <div class="summary">
            <div class="no">#{{ $order->id }}</div>
            <div>Amount Rp. {{ number_format($order->amount) }}</div>
            <div>Vendor Bill Rp. {{ number_format($order->vendor_bill) }}</div>
            <div>Revenue Rp. {{ number_format($order->revenue) }}</div>
            <div>Company: {{ $order->employee->office->company->name }}</div>
            <div>Office: {{ $order->employee->office->name }}</div>
            <div>Delivery Address: {{ $order->delivery_address }}</div>
          </div>
        </div>

        <div class="col-lg-offset-1 col-lg-6">

          <div class="meals">
            <h3>Meals</h3>

            @foreach ($groupedMeals as $date => $meals)
              <h4>{{ date_format(date_create($date), 'l, d M Y') }}</h4>
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th style="width: 80px">Qty</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($meals as $meal)
                  <tr>
                    <td>{{ $meal->name }}</td>
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
