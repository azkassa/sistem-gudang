<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Basis CRUD untuk semua modul (supply, absensi, barang, karyawan, pengguna).
 *
 * config() mengembalikan:
 *   title, add, date (kolom filter tanggal, opsional), search (kolom pencarian),
 *   order (ORDER BY), cols (kolom tabel => label), fields (kolom form).
 * Tipe field: text | number | date | time | enum | ref | password
 *   ref: options = [tabel, kolom_label]; enum: options = [nilai, ...]
 */
abstract class CrudController extends Controller
{
    abstract protected function modul(): string;

    abstract protected function modelClass(): string;

    abstract protected function config(): array;

    /** Query untuk daftar data (boleh memakai join). */
    abstract protected function baseQuery(): Builder;

    protected function aturanTambahan(?int $id): array
    {
        return [];
    }

    protected function validasiTambahan(array $data, ?int $id): void
    {
        //
    }

    /** Kembalikan teks pesan jika data tidak boleh dihapus. */
    protected function tolakHapus(int $id): ?string
    {
        return null;
    }

    /* ---------------- Hak akses ---------------- */

    protected function izin(string $aksi): bool
    {
        return (bool) auth()->user()?->izin($this->modul(), $aksi);
    }

    protected function izinkan(string $aksi): void
    {
        abort_unless($this->izin($aksi), 403, 'Anda tidak punya akses untuk aksi ini.');
    }

    /* ---------------- Aksi ---------------- */

    public function index(Request $request)
    {
        if (! $this->izin('r')) {
            // User yang hanya boleh menambah (mis. absensi) langsung diarahkan ke formulir.
            return $this->izin('c')
                ? redirect()->route($this->modul().'.create')
                : redirect()->route('dashboard');
        }

        $cfg = $this->config();
        $q = trim((string) $request->query('q', ''));
        $d = (string) $request->query('d', '');

        $query = $this->baseQuery();

        if ($q !== '') {
            $query->where(function ($w) use ($cfg, $q) {
                foreach ($cfg['search'] as $kolom) {
                    $w->orWhere($kolom, 'like', "%{$q}%");
                }
            });
        }
        if ($d !== '' && isset($cfg['date'])) {
            $query->whereDate($cfg['date'], $d);
        }

        $rows = $query->orderByRaw($cfg['order'])->limit(200)->get();

        return view('crud.index', [
            'cfg' => $cfg,
            'modul' => $this->modul(),
            'rows' => $rows,
            'q' => $q,
            'd' => $d,
            'bisa' => [
                'c' => $this->izin('c'),
                'u' => $this->izin('u'),
                'd' => $this->izin('d'),
            ],
        ]);
    }

    public function create()
    {
        $this->izinkan('c');

        return $this->tampilForm();
    }

    public function store(Request $request): RedirectResponse
    {
        $this->izinkan('c');

        return $this->simpan($request, null);
    }

    public function edit(int $id)
    {
        $this->izinkan('u');

        return $this->tampilForm($id);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->izinkan('u');

        return $this->simpan($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->izinkan('d');

        if ($pesan = $this->tolakHapus($id)) {
            return back()->with('err', $pesan);
        }

        try {
            $this->modelClass()::findOrFail($id)->delete();
        } catch (QueryException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1451) {
                return back()->with('err', 'Data tidak bisa dihapus karena masih dipakai di data lain.');
            }
            throw $e;
        }

        return redirect()->route($this->modul().'.index')->with('ok', 'Data berhasil dihapus.');
    }

    /* ---------------- Internal ---------------- */

    protected function tampilForm(?int $id = null)
    {
        $cfg = $this->config();
        $row = $id ? $this->modelClass()::findOrFail($id) : null;

        $refs = [];
        foreach ($cfg['fields'] as $k => $f) {
            if ($f['type'] === 'ref') {
                [$tabel, $kolom] = $f['options'];
                $refs[$k] = DB::table($tabel)->orderBy($kolom)->get(['id', "{$kolom} as label"]);
            }
        }

        return view('crud.form', [
            'cfg' => $cfg,
            'modul' => $this->modul(),
            'row' => $row,
            'id' => $id,
            'refs' => $refs,
            'bisaLihat' => $this->izin('r'),
        ]);
    }

    protected function simpan(Request $request, ?int $id): RedirectResponse
    {
        $cfg = $this->config();
        $data = $request->validate($this->aturan($id), $this->pesan(), $this->atribut());

        foreach ($cfg['fields'] as $k => $f) {
            if ($f['type'] === 'password') {
                if (empty($data[$k])) {
                    unset($data[$k]); // kosong saat ubah = password tidak diganti
                } else {
                    $data[$k] = Hash::make($data[$k]);
                }
            }
        }

        $this->validasiTambahan($data, $id);

        try {
            if ($id) {
                $this->modelClass()::findOrFail($id)->update($data);
            } else {
                $this->modelClass()::create($data);
            }
        } catch (QueryException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                return back()->withInput()->with('err', $this->pesanDuplikat());
            }
            throw $e;
        }

        // User yang hanya boleh menambah kembali ke formulir (seperti aplikasi lama).
        $tujuan = $this->izin('r') ? '.index' : '.create';

        return redirect()->route($this->modul().$tujuan)->with('ok', 'Data berhasil disimpan.');
    }

    protected function aturan(?int $id): array
    {
        $rules = [];

        foreach ($this->config()['fields'] as $k => $f) {
            $r = [];

            if ($f['type'] === 'password') {
                $r = [$id ? 'nullable' : 'required', 'string', 'min:6'];
            } else {
                $r[] = ($f['required'] ?? true) ? 'required' : 'nullable';

                switch ($f['type']) {
                    case 'number':
                        array_push($r, 'integer', 'min:1');
                        break;
                    case 'date':
                        $r[] = 'date';
                        break;
                    case 'time':
                        $r[] = 'date_format:H:i';
                        break;
                    case 'enum':
                        $r[] = Rule::in($f['options']);
                        break;
                    case 'ref':
                        $r[] = Rule::exists($f['options'][0], 'id');
                        break;
                    default:
                        array_push($r, 'string', 'max:'.($f['max'] ?? 255));
                }
            }

            $rules[$k] = $r;
        }

        foreach ($this->aturanTambahan($id) as $k => $tambahan) {
            $rules[$k] = array_merge($rules[$k] ?? [], $tambahan);
        }

        return $rules;
    }

    protected function pesanDuplikat(): string
    {
        return 'Data sudah ada. Periksa kode, NIP, atau kombinasi tanggal dan karyawan.';
    }

    protected function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka lebih dari 0.',
            'min.numeric' => ':attribute harus berupa angka lebih dari 0.',
            'min.string' => ':attribute minimal :min karakter.',
            'max.string' => ':attribute maksimal :max karakter.',
            'in' => ':attribute tidak valid.',
            'exists' => ':attribute tidak valid.',
            'date' => ':attribute tidak valid.',
            'date_format' => ':attribute tidak valid.',
            'unique' => $this->pesanDuplikat(),
        ];
    }

    protected function atribut(): array
    {
        $atribut = [];
        foreach ($this->config()['fields'] as $k => $f) {
            $atribut[$k] = Str::before($f['label'], ' (');
        }

        return $atribut;
    }
}
