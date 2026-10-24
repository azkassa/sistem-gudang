<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - Sistem Gudang</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login">
<form class="login-box" method="post" action="{{ route('login.proses') }}">
  @csrf
  <h1>Sistem Gudang</h1>
  <p>Masuk untuk melanjutkan.</p>
  @if (session('err'))<div class="msg err">{{ session('err') }}</div>@endif
  @if ($errors->any())
    <div class="msg err">@foreach ($errors->all() as $m)<div>{{ $m }}</div>@endforeach</div>
  @endif
  <label for="username">Username</label>
  <input id="username" name="username" value="{{ old('username') }}" autofocus required>
  <label for="password">Password</label>
  <input id="password" name="password" type="password" required>
  <button class="btn pri" type="submit">Masuk</button>
</form>
</body>
</html>
