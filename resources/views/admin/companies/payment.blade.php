@extends('layouts.admin')

@section('title', sprintf('Eatvora - %s Payment', $company->name))

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => sprintf('%s Payment', $company->name)])

        <div class="col-lg-12">
          <div class="action-panel">
            <add-balance-button></add-balance-button>
          </div>
        </div>

        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Employee Count</th>
                <th>Amount / Employee</th>
                <th>Note</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payments as $payment)
              <tr>
                <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
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
  <add-balance-modal :company-id="{{ $company->id }}"></add-balance-modal>
  <payment-note-modal></payment-note-modal>
@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
@endpush
