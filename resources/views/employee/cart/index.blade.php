@extends('layouts.employee')

@section('body')
  <div class="meal-list">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-title">Keranjang</h1>
        </div>
      </div>
      <div class="row" style="margin-top: 22px;">
        <div class="col-xs-12 col-sm-9">
          <cart></cart>
        </div>
        <div class="col-xs-12 col-sm-3">
          <cart-summary></cart-summary>
        </div>
      </div>
    </div>
  </div>
@endsection
