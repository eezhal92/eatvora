@extends('layouts.admin')

@section('title', 'Eatvora - Payment')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Payment'])

        <div class="col-lg-12">
          <div class="action-panel">
            <form class="form-inline" action="{{ url('/ap/payments') }}">
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
                <th>Date</th>
                <th>Company</th>
                <th>Total Amount</th>
                <th>For Employee</th>
                <th>Amount / Employee</th>
                <th>Note</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payments as $payment)
              <tr>
                <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
                <td><a href="{{ url(sprintf('/ap/companies/%d', $payment->company_id)) }}">{{ $payment->company->name }}</a></td>
                <td>Rp. {{ number_format($payment->total_amount) }}</td>
                <td>{{ $payment->employee_count }}</td>
                <td>Rp. {{ number_format($payment->amount_per_employee) }}</td>
                <td>
                  <payment-note-button :payment="{{ $payment }}"></payment-note-button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-center">
              {{ $payments->appends(request()->except('page'))->render() }}
            </div>
        </div>

      </div>
    </div>
  </div>

@endsection

@section('modals')
  <payment-note-modal></payment-note-modal>
@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
@endpush
