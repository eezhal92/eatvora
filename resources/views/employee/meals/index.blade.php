@extends('layouts.employee')

@section('body')
  <div class="meal-list">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-title">Katalog</h1>
        </div>
      </div>
      <div class="row" style="margin-top: 22px">
        <div class="col-xs-12 col-sm-3">
          <meal-filter></meal-filter>
        </div>
        <div class="col-xs-12 col-sm-9">
          <meal-list :weekdays="{{ $nextWeekDayDates }}"></meal-list>
        </div>
      </div>
    </div>
  </div>
@endsection
