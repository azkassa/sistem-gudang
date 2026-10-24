@extends('layouts.app')

@section('title', $cfg['title'].' - Sistem Gudang')

@section('content')
  <h2>{{ $id ? 'Ubah' : 'Tambah' }} {{ strtolower($cfg['title']) }}</h2>
  <p class="sub">Isi data di bawah, lalu klik Simpan.</p>

  @if ($errors->any())
    <div class="msg err">@foreach ($errors->all() as $m)<div>{{ $m }}</div>@endforeach</div>
  @endif

  <form class="form" method="post" action="{{ $id ? route($modul.'.update', $id) : route($modul.'.store') }}">
    @csrf
    @if ($id) @method('PUT') @endif

    @foreach ($cfg['fields'] as $k => $f)
      @php
        $tipe = $f['type'];
        $val = $tipe === 'password'
            ? ''
            : old($k, data_get($row, $k, ($tipe === 'date') ? today()->toDateString() : ''));
        if ($tipe === 'time') { $val = substr((string) $val, 0, 5); }
        $wajib = $tipe === 'password' ? ! $id : ($f['required'] ?? true);
      @endphp
      <div class="row">
        <label for="{{ $k }}">{{ $f['label'] }}{{ $wajib ? '' : ($tipe === 'password' ? ' (kosongkan jika tidak diganti)' : ' (opsional)') }}</label>
        @if ($tipe === 'enum')
          <select id="{{ $k }}" name="{{ $k }}">
            @foreach ($f['options'] as $o)
              <option value="{{ $o }}" @selected((string) $val === (string) $o)>{{ ucfirst($o) }}</option>
            @endforeach
          </select>
        @elseif ($tipe === 'ref')
          <select id="{{ $k }}" name="{{ $k }}" required>
            <option value="">Pilih...</option>
            @foreach ($refs[$k] as $o)
              <option value="{{ $o->id }}" @selected((string) $val === (string) $o->id)>{{ $o->label }}</option>
            @endforeach
          </select>
        @else
          <input id="{{ $k }}" name="{{ $k }}" type="{{ $tipe }}" value="{{ $val }}"
                 @if ($tipe === 'number') min="1" @endif
                 @if ($tipe === 'password') minlength="6" autocomplete="new-password" @endif
                 @if ($wajib) required @endif>
        @endif
      </div>
    @endforeach

    <button class="btn pri" type="submit">Simpan</button>
    <a class="btn" href="{{ $bisaLihat ? route($modul.'.index') : route('dashboard') }}">Batal</a>
  </form>
@endsection
