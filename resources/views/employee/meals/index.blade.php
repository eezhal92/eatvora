@extends('layouts.employee')

@section('body')
  <div class="meal-list" style="padding: 22px 0">
    <div class="container container--small">
      <div class="row">
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
