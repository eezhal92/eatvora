@extends('layouts.admin')

@section('title', 'Eatvora - Create Meal Schedule')

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Create Meal Schedule'])

        <div class="col-lg-6">
          <form action="{{ url('/ap/schedules') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="vendor">Vendor</label>
              <select name="vendor" class="form-control">
                <option value="">Choose Vendor</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group {{ $errors->first('menu_id', 'has-error') }}">
              <label for="name">Menu</label>
              <select name="menu_id" class="form-control"></select>
              @if ($errors->has('menu_id'))
                <span class="help-block">{{ $errors->first('menu_id') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('qty', 'has-error') }}">
              <label for="qty">Quantity</label>
              <input id="qty" type="text" name="qty" class="form-control" value="{{ old('qty')}}">
              @if ($errors->has('qty'))
                <span class="help-block">{{ $errors->first('qty') }}</span>
              @endif
            </div>

            <div class="form-group {{ $errors->first('date', 'has-error') }}">
              <label for="date">Date</label>
              <input id="date" type="date" name="date" class="form-control" value="{{ old('date')}}">
              @if ($errors->has('date'))
                <span class="help-block">{{ $errors->first('date') }}</span>
              @endif
            </div>

            <button class="btn btn-primary">
              Create Schedule
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
  <script>
    'use strict';

    const $menu = $('select[name=menu_id]');

    function getMenusByVendor(vendorId) {
      return axios.get(`/api/v1/menus?vendor_id=${vendorId}`)
        .then(response => response.data.data);
    }

    function createOptions(menus) {
      return menus.map(menu => `<option value="${menu.id}">${menu.name}</option>`)
    }

    function clearAndAppendOptions(menus) {
      $menu.empty().append(createOptions(menus));
    }

    function defaultMenuOptions() {
      clearAndAppendOptions([{ id: '', name: 'Choose Vendor First' }]);
    }

    defaultMenuOptions();

    $('select[name=vendor]').on('change', function (e) {
      if (!e.target.value) {
        defaultMenuOptions();

        return;
      }

      getMenusByVendor(e.target.value)
        .then(clearAndAppendOptions)
    });
  </script>
@endpush
