@extends('layouts.admin')

@section('title', 'Eatvora - Order')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Order'])

        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-12">
              <h4>Next Week Stats</h4>
              <br>
            </div>
            <div class="col-lg-4">
              <div class="stats-widget">
                <div class="stats-widget__number">Rp. {{ number_format($totalOrder) }}</div>
                <div class="stats-widget__title">Total Orders</div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="stats-widget">
                <div class="stats-widget__number">Rp. {{ number_format($totalVendorBill) }}</div>
                <div class="stats-widget__title">Total Vendor Bill</div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="stats-widget">
                <div class="stats-widget__number">Rp. {{ number_format($totalRevenue) }}</div>
                <div class="stats-widget__title">Total Revenue</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="action-panel">
            <form class="form-inline" action="{{ url('/ap/orders') }}">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">From</span>
                  <input id="date-from" class="form-control" type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">To</span>
                  <input id="date-to" class="form-control" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
              </div>
              <button class="pull-right btn btn-default" type="submit" style="margin-left: 5px">Search</button>
              <a href="{{ url('/ap/payments') }}" class="pull-right btn btn-default" type="submit">Reset</a>
            </form>
          </div>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Order By</th>
                <th>Delivery Address</th>
                <th>Amount</th>
                <th>Vendor Bill</th>
                <th>Revenue</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
                <tr>
                  <td>#{{ $order->id }}</td>
                  <td>{{ $order->employee->user->name }}</td>
                  <td>{{ $order->delivery_address }}</td>
                  <td>Rp. {{ number_format($order->amount) }}</td>
                  <td>Rp. {{ number_format($order->vendor_bill) }}</td>
                  <td>Rp. {{ number_format($order->revenue) }}</td>
                  <td><a href="{{ url('/ap/orders/' . $order->id) }}">View</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-center">
              {{ $orders->appends(request()->except('page'))->render() }}
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
