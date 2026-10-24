@extends('layouts.app')

@section('title', $cfg['title'].' - Sistem Gudang')

@section('content')
  <h2>{{ $cfg['title'] }}</h2>
  <p class="sub">{{ $rows->count() }} data ditampilkan</p>
  <form class="bar" method="get" action="{{ route($modul.'.index') }}">
    <input class="grow" type="search" name="q" value="{{ $q }}" placeholder="Cari data...">
    @isset($cfg['date'])
      <input type="date" name="d" value="{{ $d }}" aria-label="Filter tanggal">
    @endisset
    <button class="btn" type="submit">Cari</button>
    @if ($q !== '' || $d !== '')
      <a class="btn" href="{{ route($modul.'.index') }}">Reset</a>
    @endif
    @if ($bisa['c'])
      <a class="btn pri" href="{{ route($modul.'.create') }}">+ {{ $cfg['add'] }}</a>
    @endif
  </form>
  <div class="card">
  @if ($rows->isEmpty())
    <div class="empty">Belum ada data{!! $bisa['c'] ? '. Klik “'.e($cfg['add']).'” untuk mulai.' : ' pada tanggal ini.' !!}</div>
  @else
    <table>
      <tr>
        @foreach ($cfg['cols'] as $label)<th>{{ $label }}</th>@endforeach
        <th></th>
      </tr>
      @foreach ($rows as $r)
        <tr>
          @foreach ($cfg['cols'] as $k => $label)
            <td>@include('crud._cell', ['k' => $k, 'v' => $r->{$k}])</td>
          @endforeach
          <td class="act">
            @if ($bisa['u'])
              <a class="btn sm" href="{{ route($modul.'.edit', $r->id) }}">Ubah</a>
            @endif
            @if ($bisa['d'])
              <form method="post" action="{{ route($modul.'.destroy', $r->id) }}" onsubmit="return confirm('Hapus data ini?')">
                @csrf @method('DELETE')
                <button class="btn sm del" type="submit">Hapus</button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
    </table>
  @endif
  </div>
@endsection
