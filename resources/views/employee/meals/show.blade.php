@extends('layouts.employee')

@section('body')
  <div class="meal-list" style="padding: 40px">
    <div class="container container--small">
      <div class="row">
        <div class="col-xs-12 col-sm-4">
          <img class="img-responsive" src="http://lorempixel.com/600/480/food" />
        </div>
        <div class="col-xs-12 col-sm-8">
          <h3 class="page-sub-title" style="margin-top: 0">Kamis, 25 Desember 2017</h3>
          <h1>{{ $menu->name }}</h1>
          <p style="font-size: 16px; font-weight: bold">Oleh {{ $menu->vendorName() }}</p>
          <p style="font-size: 18px; font-weight: bold">{{ $menu->formattedPrice() }}</p>
          <button class="btn btn-lg btn--primary">Ingin Ini</button>
          <br>
          <br>
          <p style="font-weight: bold; font-size: 18px">Deskripsi</p>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis placeat aliquam provident, officiis qui dicta quidem voluptates ex! Nemo voluptatem, dignissimos quisquam fugiat quidem modi veritatis, optio saepe. Excepturi, dolorum.</p>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('afterScripts')
  <script type="text/javascript">
    axios.get('/api/v1/meals?date=2017-06-19')
      .then(response => {
        console.log(response.data);
      })
  </script>
@endpush
