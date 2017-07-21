<h1>Create New Company</h1>

<form action="{{ url('/ap/companies') }}" method="post">
  {{ csrf_field() }}

  @if($errors->any())
    {{ dump($errors) }}
  @endif

  <h3>Company</h3>

  <div class="form-group">
    <label for="company_name">Name</label>
    <input id="company_name" type="text" name="company_name" class="form-control">
  </div>

  <div class="form-group">
    <label for="company_address">Address</label>
    <textarea name="company_address" id="company_address" class="form-control"></textarea>
  </div>

  <h3>Admin of Company</h3>

  <div class="form-group">
    <label for="admin_name">Name</label>
    <input id="admin_name" type="text" name="admin_name" class="form-control">
  </div>

  <div class="form-group">
    <label for="admin_email">Email</label>
    <input id="admin_email" type="text" name="admin_email" class="form-control">
  </div>

  <button class="btn btn-primary">
    Create New Company
  </button>
</form>
