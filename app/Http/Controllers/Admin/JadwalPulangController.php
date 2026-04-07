<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPulang;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class JadwalPulangController extends Controller
{
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

        $jadwal = $this->getJadwalByKelas($activeKelas);

        return view('admin.edit-jadwal-pulang', compact('jadwal', 'activeKelas'));
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

        return redirect()->route('jadwal-pulang.edit', ['kelas' => $kelas])
            ->with('success', 'Jadwal berhasil disimpan.');
    }
}