@extends('layouts.admin')

@section('title', 'Eatvora - Edit ' . $menu->name)

@section('content')

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => 'Edit ' . $menu->name])

        <form action="{{ url("/ap/menus/" . $menu->id) }}" method="post" enctype="multipart/form-data">

          <div class="col-lg-4">
            <div class="file-uploader" style="text-align: center;">
              @if ($menu->image_path)
              <img src="{{ asset('storage/'.$menu->image_path) }}" style="width: 100%" class="img-responsive file-uploader__img" alt="">
              @else
              <img src="{{ asset('images/menu-placeholder.png' )}}" style="width: 100%" class="img-responsive
              file-uploader__img" alt="">
              @endif
              <label for="menu-image" style="cursor: pointer;  padding: 10px 0; font-size: 12px; color: #006bd6"><i class="icon-picture"></i> Change Image</label>
              <input type="file" id="menu-image" name="image" style="display: none">
            </div>
          </div>

          <div class="col-lg-offset-1 col-lg-6">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}

              <div class="form-group {{ $errors->first('name', 'has-error') }}">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $menu->name)}}">
                @if ($errors->has('name'))
                  <span class="help-block">{{ $errors->first('name') }}</span>
                @endif
              </div>

              <div class="form-group {{ $errors->first('vendor', 'has-error') }}">
                <label for="vendor">Vendor</label>
                <select name="vendor" id="vendor" class="form-control">
                  @foreach ($vendors as $vendorId => $vendorName)
                  <option value="{{ $vendorId }}" {{ old('vendor', $menu->vendor_id) == $vendorId ? 'selected' : '' }}>
                    {{ $vendorName }}
                  </option>
                  @endforeach
                </select>
                @if ($errors->has('vendor'))
                  <span class="help-block">{{ $errors->first('vendor') }}</span>
                @endif
              </div>

              <div class="form-group {{ $errors->first('price', 'has-error') }}">
                <label for="price">Price</label>
                <input id="price" type="number" min="10" name="price" class="form-control" value="{{ old('price', $menu->price)}}">
                @if ($errors->has('price'))
                  <span class="help-block">{{ $errors->first('price') }}</span>
                @endif
              </div>

              <div class="form-group {{ $errors->first('description', 'has-error') }}">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $menu->address) }}</textarea>
                @if ($errors->has('description'))
                  <span class="help-block">{{ $errors->first('description') }}</span>
                @endif
              </div>

              <div class="form-group {{ $errors->first('contents', 'has-error') }}">
                <label for="contents">Contents</label>
                <textarea name="contents" id="contents" class="form-control">{{ old('contents', $menu->contents) }}</textarea>
                @if ($errors->has('contents'))
                  <span class="help-block">{{ $errors->first('contents') }}</span>
                @endif
              </div>

              <button class="btn btn-primary">
                Update Menu
              </button>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="/js/admin/admin.js"></script>
  <script>
    const $imgEl = $('.file-uploader__img');

    $('#menu-image').on('change', (e) => {
      const files = e.target.files;

      const reader = new FileReader();

      reader.onload = (e) => {
        $imgEl.attr('src', e.target.result);
      };

      reader.readAsDataURL(files[0]);
    });
  </script>
@endpush
