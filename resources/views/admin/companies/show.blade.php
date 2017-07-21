<h1>{{ $company->name }}</h1>

<div class="offices">
  @foreach($company->offices as $office)
    <div class="office-item">
      <h2>{{ $office->name }}</h2>
    </div>
  @endforeach
</div>
