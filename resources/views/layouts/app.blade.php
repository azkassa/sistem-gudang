<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Sistem Gudang')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<aside>
  <h1>Sistem Gudang</h1>
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'on' : '' }}">Ringkasan</a>
  @foreach (config('gudang.menu') as $k => $judul)
    @if (auth()->user()->izin($k, 'r') || auth()->user()->izin($k, 'c'))
      <a href="{{ route($k.'.index') }}" class="{{ request()->routeIs($k.'.*') ? 'on' : '' }}">{{ $judul }}</a>
    @endif
  @endforeach
  <div class="who"><b>{{ auth()->user()->nama }}</b><br>
    {{ auth()->user()->role === 'admin' ? 'Pengelola' : 'User' }} ·
    <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
  </div>
</aside>
<main>
  @if (session('ok'))<div class="msg ok">{{ session('ok') }}</div>@endif
  @if (session('err'))<div class="msg err">{{ session('err') }}</div>@endif
  @yield('content')
</main>
</body>
</html>
