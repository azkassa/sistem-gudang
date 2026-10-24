@if ($v === null || $v === '')
<span class="mute">–</span>
@elseif (str_starts_with($k, 'tanggal'))
{{ \Illuminate\Support\Carbon::parse($v)->translatedFormat('d M Y') }}
@elseif (str_starts_with($k, 'jam_'))
{{ substr($v, 0, 5) }}
@elseif (in_array($k, ['jenis', 'status', 'role']))
<span class="badge {{ strtolower($v) }}">{{ ucfirst($v) }}</span>
@elseif ($k === 'jumlah')
<b>{{ number_format((int) $v, 0, ',', '.') }}</b>
@else
{{ $v }}
@endif
