@if (session('error') || session('success'))
  <div class="row">
    <div class="col-xs-12 col-sm-12">
      @if (session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
    </div>
  </div>
@endif
