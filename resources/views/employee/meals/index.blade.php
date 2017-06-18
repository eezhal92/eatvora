@extends('layouts.master')

@section('body')
  <p>Meals {{ auth()->user() ? auth()->user()->name : 'Not Logged In' }}</p>
  <cart></cart>
  <meal-list :weekdays="{{ $nextWeekDayDates }}"></meal-list>
@endsection