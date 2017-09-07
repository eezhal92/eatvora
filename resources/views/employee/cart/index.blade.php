@extends('layouts.employee')

@section('title', 'Keranjang - Eatvora')

@section('body')
  <div class="cart-items">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-title">Keranjang</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <alert id="checkout" type="danger"></alert>
        </div>
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
