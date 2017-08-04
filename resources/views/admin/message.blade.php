<div class="col-lg-12">
  <div style="margin-bottom: 20px">

    @if (session()->has('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    @if (session()->has('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

  </div>
</div>
