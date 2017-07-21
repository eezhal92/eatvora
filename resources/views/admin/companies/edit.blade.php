<h1>Edit {{ $company->name }}</h1>

<form action="{{ url("/ap/companies/{$company->id}") }}" method="post">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
  <div class="form-group">
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $company->name) }}">
  </div>
  <h3>Main Office</h3>
  <div class="form-group">
    <label for="main_office_name">Name</label>
    <input id="main_office_name" name="main_office_name" type="text" value="{{ old('main_office_name', $company->mainOffice()->name) }}">
  </div>
  <div class="form-group">
    <label for="main_office_address">Address</label>
    <textarea id="main_office_address" name="main_office_address" type="text">{{ old('main_office_address', $company->mainOffice()->address) }}</textarea>
  </div>
  <button class="btn btn-primary">
    Update Company
  </button>
</form>
