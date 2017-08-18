@extends('layouts.admin')

@section('title', 'Eatvora - Order')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Order'])

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

          @foreach($menus as $menu)
          <div class="menu-orders">
            <h4>{{ $menu->name }} - {{ $menu->date }}</h4>
            <table class="table">
              <thead>
                <tr>
                  <th>Customer Name</th>
                  <th>Company</th>
                  <th>Office</th>
                  <th>Delivery Address</th>
                  <th>qty</th>
                  <th>price</th>
                </tr>
              </thead>
              <tbody>
              @php

              $orders = $menu->orders();
              $total = $orders->map(function ($item) {
                return $item->qty * $item->menu->price;
              })->sum();

              @endphp
              @foreach($orders as $order)
                <tr>
                  <td>{{ $order->customer_name }}</td>
                  <td>{{ $order->company_name }}</td>
                  <td>{{ $order->office_name }}</td>
                  <td>{{ $order->delivery_address }}</td>
                  <td>{{ $order->qty }}</td>
                  <td>{{ $order->menu->formattedPrice() }}</td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5">Total</td>
                  <td>Rp. {{ number_format($total) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
          @endforeach

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
