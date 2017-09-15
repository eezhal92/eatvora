@extends('layouts.employee')

@section('title' , 'Beranda - Eatvora')

@push('head')
  <style>
    .no-plan {
      text-align: center;
      padding: 40px;
    }

    .meal-day {
      margin-bottom: 20px;
    }

    .meal-day__date {
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .meal-item {
      overflow: hidden;
      margin-bottom: 16px;
      background: #fff;
      border-radius: 3px;
      box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    }

    .meal-item__img-container {
      display: inline-block;
      background: #ccc;
      width: 110px;
      height: 100px;
      float: left;
    }

    .meal-item__detail {
      float: left;
      display: inline-block;
      margin-left: 12px;
      margin-top: 5px;
    }

    .meal-item__title {
      font-size: 14px;
      font-weight: bold;
      margin-top: 10px;
    }

    .meal-item__vendor {
      font-size: 12px;
      margin-top: 5px;
    }

    .meal-item__qty {
      margin-top: 5px;
      font-size: 12px;
    }

    .balance-panel {
      background: #fff;
      border-radius: 4px;
      box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1)
    }

    .current-balance, .balance-logs {
      padding: 26px 15px;
    }

    .home-panel__heading {
      margin-top: 0;
      margin-bottom: 16px;
      font-weight: bold;
      font-size: 16px;
      color: #86919F;
    }

    .balance-logs__table {
       font-size: 12px;
       width: 100%;
    }

    .balance-logs tr td {
      padding: 4px;
    }

    .balance-logs tr td:first-child {
      padding-left: 0px;
    }

    .balance-logs tr td:last-child {
      padding-right: 0px;
      text-align: right;
    }

    .balance-added {
      color: #4CAF50;
    }

    .home-sub-title {
      margin-top: 0;
      font-weight: bold;
      font-size: 16px;
      color: #86919F;
      margin-top: 26px;
      margin-bottom: 24px;
    }

    .balance-log__action {
      text-align: center;
      margin-top: 18px;
    }

    .balance-desc {
      cursor: pointer;
    }
  </style>
@endpush

@section('body')
<div class="container container--small">

  <div class="row">
    <div class="col-xs-12">
      <div class="page-title-greeting" style="margin-bottom: 28px">
        <h1 class="page-title">
          Beranda
          <span style="font-size: 16px" class="pull-right">Halo {{ auth()->user()->name }}!</span>
        </h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-4">
      <h2 class="home-sub-title">Minggu Ini</h2>

      <my-meal for="this-week">
        <div slot="noMealMessage" class="no-plan">
          <p>Tidak ada pesanan makan siang...</p>
        </div>
      </my-meal>

    </div>
    <div class="col-xs-12 col-sm-4">
      <h2 class="home-sub-title">Minggu Depan</h2>

      <my-meal for="next-week">
        <div slot="noMealMessage" class="no-plan">
          <p>Ooops! Belum pesan siang minggu depan</p>
          <br />
          <a href="/meals" class="btn btn--primary btn-sm">
            <i class="glyphicon glyphicon-eye-open"></i> Lihat Katalog</a>
        </div>
      </my-meal>

    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="balance-panel">
        <div class="current-balance">
          <h2 class="home-panel__heading">Balance</h2>
          <p>Jumlah balance Anda sekarang</p>
          <p style="font-size: 28px; margin: 0">{{ auth()->user()->balance() / config('eatvora.rupiah_per_point') }} Poin</p>
        </div>
        <hr style="margin: 0">
        <div class="balance-logs">
          <h2 class="home-panel__heading">Aktivitas</h2>
          <table class="balance-logs__table">
            <tbody>
              @foreach ($balances as $balance)
              <tr>
                <td>{{ $balance->created_at->format('d/m') }}</td>
                <td>
                  <span class="balance-desc" data-toggle="tooltip" data-placement="bottom" title="{{ $balance->description }}">{{ str_limit($balance->description, 14, '...') }}</span>
                </td>
                <td>
                  @if ($balance->amount > 0)
                    <span class="balance-added">{{ $balance->amountInPoint() }}</span>
                  @else
                    <span>{{ $balance->amountInPoint() }}</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('afterScripts')
  <script>
    $('.balance-desc').tooltip();
  </script>
@endpush
