@extends('layouts.master')

@section('body')
  <p>{{ $menu->name }}</p>
  <p>{{ $menu->vendorName() }}</p>
  <p>{{ $menu->formattedPrice() }}</p>
@endsection

@push('afterScripts')
  <script type="text/javascript">
    axios.get('/api/v1/meals?date=2017-06-19')
      .then(response => {
        console.log(response.data);
      })
  </script>
@endpush