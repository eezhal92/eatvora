<h1>Company List</h1>

<ul>
  @foreach($companies as $company)
  <li>{{ $company->name }}</li>
  @endforeach
</ul>
