<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPulang;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalPulangController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    // =========================
    // TAMPILKAN HALAMAN
    // =========================
    public function index(Request $request)
    {
        $activeKelas = (int) $request->query('kelas', 1);
        $activeKelas = max(1, min(6, $activeKelas));

        $jadwal = $this->getJadwalByKelas($activeKelas);

        return view('admin.jadwal_pulang', compact('jadwal', 'activeKelas'));
    }

   public function edit(Request $request)
    {
        $activeKelas = (int) $request->query('kelas', 1);
        $activeKelas = max(1, min(6, $activeKelas));

        $hari = $request->query('hari', 'Senin');

        $jadwal = JadwalPulang::where('kelas', $activeKelas)
            ->where('hari', $hari)
            ->first();

        return view('admin.edit-jadwal-pulang', [
            'jadwal' => $jadwal,
            'activeKelas' => $activeKelas,
            'hariDipilih' => $hari,
        ]);
    }

    public function updateSatu(Request $request)
    {
        $request->validate([
            'kelas' => 'required|integer|min:1|max:6',
            'hari' => 'required|string',
            'jam' => 'nullable|date_format:H:i',
            'alasan' => 'nullable|string',
            'kelas_tujuan' => 'required|array|min:1',
            'kelas_tujuan.*' => 'integer|min:1|max:6',
        ]);

        foreach ($request->kelas_tujuan as $kelasTujuan) {

            JadwalPulang::updateOrCreate(
                [
                    'kelas' => $kelasTujuan,
                    'hari' => $request->hari,
                ],
                [
                    'jam' => $request->jam,
                    'libur' => false,
                ]
            );

            $this->kirimNotifikasiJadwal(
                $kelasTujuan,
                $request->hari,
                $request->jam,
                $request->alasan
            );
        }

        return redirect()
            ->route('jadwal-pulang', [
                'kelas' => $request->kelas
            ])
            ->with(
                'success',
                'Jadwal berhasil diperbarui dan notifikasi telah dikirim'
            );
    }

private function kirimNotifikasiJadwal($kelas, $hari, $jam, $alasan)
{
    $siswaWali = DB::table('siswa')
    ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
    ->join('siswa_wali', 'siswa.id_siswa', '=', 'siswa_wali.id_siswa')
    ->join('wali', 'siswa_wali.id_wali', '=', 'wali.id_wali')
    ->where('kelas.nama_kelas', 'like', $kelas . '%')
    ->where('siswa.is_active', 1)
    ->select(
        'siswa.id_siswa',
        'siswa.nama_siswa',
        'wali.id_wali',
        'wali.nama_wali',
        'wali.no_hp'
    )
    ->get();
   
    $alasanText = $alasan ? " dikarenakan {$alasan}" : '';

    foreach ($siswaWali as $data) {
        if (!$data->no_hp) continue;

       $pesan = "Assalamu'alaikum Wr. Wb.\n"
       . "Yth. Bapak/Ibu {$data->nama_wali},\n\n"
       . "Kami informasikan bahwa jadwal pulang {$data->nama_siswa} pada hari {$hari} berubah menjadi pukul {$jam}{$alasanText}.\n\n"
       . "Terima kasih.";

        $hasil = $this->fonnte->kirim($data->no_hp, $pesan);

        DB::table('notifikasi')->insert([
            'id_user'    => auth()->id() ?? 1,
            'id_siswa'   => $data->id_siswa,
            'id_wali'    => $data->id_wali,
            'judul'      => 'Perubahan Jadwal Pulang',
            'pesan'      => $pesan,
            'tipe'       => 'jadwal_pulang',
            'status'     => 'terkirim',
            'is_pushed'  => 1,
            'tipe_notif' => 'wa',
            'status_wa'  => isset($hasil['status']) && $hasil['status'] ? 'sukses' : 'gagal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

    private function getJadwalByKelas(int $kelas): array
    {
        $this->ensureDefaultJadwalExists($kelas);

        return JadwalPulang::where('kelas', $kelas)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->get()
            ->map(function (JadwalPulang $item) {
                $jam = $item->jam;

                if (!is_null($jam) && $jam !== '') {
                    $jam = Carbon::parse($jam)->format('H:i');
                }

                return [
                    'hari' => $item->hari,
                    'jam' => $jam,
                    'libur' => $item->libur,
                ];
            })->keyBy('hari')
            ->toArray();
    }

    private function ensureDefaultJadwalExists(int $kelas): void
    {
        if (JadwalPulang::where('kelas', $kelas)->exists()) {
            return;
        }

        $defaults = [
            ['hari' => 'Senin', 'jam' => '10:30'],
            ['hari' => 'Selasa', 'jam' => '10:30'],
            ['hari' => 'Rabu', 'jam' => '10:30'],
            ['hari' => 'Kamis', 'jam' => '10:30'],
            ['hari' => 'Jumat', 'jam' => '09:30'],
            ['hari' => 'Sabtu', 'jam' => '10:30'],
        ];

        foreach ($defaults as $row) {
            JadwalPulang::create([
                'kelas' => $kelas,
                'hari' => $row['hari'],
                'jam' => $row['jam'],
                'libur' => false,
            ]);
        }
    }

    // =========================
    // (OPSIONAL) SIMPAN DATA
    // =========================
    public function update(Request $request, int $kelas)
    {
        $kelas = max(1, min(6, $kelas));

        $validated = $request->validate([
            'jadwal' => 'required|array',
            'jadwal.*.jam' => 'nullable|date_format:H:i',
            'jadwal.*.libur' => 'required|in:0,1',
        ]);

        foreach ($validated['jadwal'] as $hari => $data) {
            if ($data['libur'] === '0' && empty($data['jam'])) {
                throw ValidationException::withMessages([
                    "jadwal.$hari.jam" => 'Jam harus diisi ketika tidak libur.',
                ]);
            }

            JadwalPulang::updateOrCreate(
                ['kelas' => $kelas, 'hari' => $hari],
                [
                    'jam' => $data['libur'] === '1' ? null : $data['jam'],
                    'libur' => $data['libur'] === '1',
                ]
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil disimpan.']);
        }

        return redirect()->route('jadwal-pulang', ['kelas' => $kelas])
            ->with('success', 'Jadwal berhasil disimpan.');
    }
}
