@extends('layouts.app')

@section('content')
  <h2>Ringkasan hari ini</h2>
  <p class="sub">{{ now()->translatedFormat('d F Y') }}</p>
  <div class="stats">
    <div class="stat"><b>{{ number_format($masukHariIni, 0, ',', '.') }}</b><span>Barang masuk hari ini</span></div>
    <div class="stat"><b>{{ number_format($eksporHariIni, 0, ',', '.') }}</b><span>Barang diekspor hari ini</span></div>
    <div class="stat"><b>{{ $hadir }}</b><span>Karyawan hadir</span></div>
  </div>
  <div class="bar">
    @if (auth()->user()->izin('supply', 'c'))
      <a class="btn pri" href="{{ route('supply.create') }}">+ Catat supply</a>
    @endif
    <a class="btn {{ auth()->user()->izin('supply', 'c') ? '' : 'pri' }}" href="{{ route('absensi.create') }}">+ Isi daftar hadir</a>
  </div>
  <h3>Stok barang saat ini</h3>
  <div class="card">
  @if ($stok->isEmpty())
    <div class="empty">Belum ada barang. Tambahkan di menu Data Barang.</div>
  @else
    <table>
      <tr><th>Kode</th><th>Barang</th><th>Total masuk</th><th>Total ekspor</th><th>Stok</th></tr>
      @foreach ($stok as $r)
        @php($masuk = (int) $r->masuk) @php($ekspor = (int) $r->ekspor)
        <tr>
          <td>{{ $r->kode }}</td><td>{{ $r->nama }}</td>
          <td>{{ number_format($masuk, 0, ',', '.') }}</td>
          <td>{{ number_format($ekspor, 0, ',', '.') }}</td>
          <td><b>{{ number_format($masuk - $ekspor, 0, ',', '.') }}</b> {{ $r->satuan }}</td>
        </tr>
      @endforeach
    </table>
  @endif
  </div>
@endsection
