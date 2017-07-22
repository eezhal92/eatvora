<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex">
  <title>Login</title>
</head>
<body>
  <form action="{{ url('/ap/login') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="email">Email</label>
      <input id="email" type="email" name="email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" type="password" name="password">
    </div>
    <div class="form-group">
      <button class="btn btn-block btn-primary">
        Log In
      </button>
    </div>
  </form>
</body>
</html>
