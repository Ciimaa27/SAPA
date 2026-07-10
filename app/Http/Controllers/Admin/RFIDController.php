<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RFIDController extends Controller
{
    // =====================================================
    // HALAMAN RFID / SIDIK JARI
    // =====================================================
    public function index(Request $request, $tab = 'rfid')
    {
        $search = trim($request->input('search', ''));

        // =================================================
        // TAB RFID
        // =================================================
        if ($tab === 'rfid') {
            /*
            |--------------------------------------------------------------------------
            | QUERY DASAR RFID
            |--------------------------------------------------------------------------
            | Query dasar dibuat tanpa orderBy terlebih dahulu supaya pencarian
            | nomor urut bisa mengambil data berdasarkan urutan yang sama.
            */
            $baseQuery = DB::table('siswa')
                ->select('id_siswa', 'nama_siswa', 'rfid_uid')
                ->where('is_active', 1);

            // =================================================
            // QUERY UTAMA
            // =================================================
            $query = clone $baseQuery;

            // =================================================
            // SEARCH RFID
            // =================================================
            if ($search !== '') {
                /*
                |--------------------------------------------------------------------------
                | JIKA SEARCH ANGKA
                |--------------------------------------------------------------------------
                | Contoh: search 13 -> Maka sistem mengambil data urutan ke-13 dari
                | seluruh data, bukan hanya halaman pagination yang sedang dibuka.
                */
                if (ctype_digit($search)) {
                    $nomor = (int) $search;
                    if ($nomor > 0) {
                        $target = (clone $baseQuery)
                            ->orderBy('nama_siswa', 'asc')
                            ->offset($nomor - 1)
                            ->limit(1)
                            ->first();

                        if ($target) {
                            $query->where('id_siswa', $target->id_siswa);
                        } else {
                            $query->whereRaw('1 = 0'); // Jika nomor tidak ditemukan
                        }
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | SEARCH TEKS
                    |--------------------------------------------------------------------------
                    | Cari berdasarkan: Nama siswa, UID RFID
                    */
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_siswa', 'like', "%{$search}%")
                          ->orWhere('rfid_uid', 'like', "%{$search}%");
                    });
                }
            }

            // =================================================
            // PAGINATION RFID
            // =================================================
            $data = $query->orderBy('nama_siswa', 'asc')->paginate(10)->withQueryString();
            return view('admin.rfid', compact('data', 'tab', 'search'));
        }

        // =================================================
        // TAB SIDIK JARI
        // =================================================
        elseif ($tab === 'sidik-jari') {
            /*
            |--------------------------------------------------------------------------
            | QUERY DASAR SIDIK JARI
            |--------------------------------------------------------------------------
            */
            $baseQuery = DB::table('wali')
                ->leftJoin('siswa_wali', 'wali.id_wali', '=', 'siswa_wali.id_wali')
                ->leftJoin('siswa', 'siswa_wali.id_siswa', '=', 'siswa.id_siswa')
                ->select(
                    'wali.id_wali',
                    'wali.nama_wali',
                    'wali.fingerprint_id',
                    'siswa_wali.hubungan',
                    'siswa.id_siswa',
                    'siswa.nama_siswa'
                )
                ->where('wali.is_active', 1);

            // =================================================
            // QUERY UTAMA
            // =================================================
            $query = clone $baseQuery;

            // =================================================
            // SEARCH SIDIK JARI
            // =================================================
            if ($search !== '') {
                /*
                |--------------------------------------------------------------------------
                | SEARCH NOMOR URUT
                |--------------------------------------------------------------------------
                | Contoh: search 13 -> Sistem mengambil baris urutan ke-13 dari seluruh data wali.
                */
                if (ctype_digit($search)) {
                    $nomor = (int) $search;
                    if ($nomor > 0) {
                        $target = (clone $baseQuery)
                            ->orderBy('wali.nama_wali', 'asc')
                            ->offset($nomor - 1)
                            ->limit(1)
                            ->first();

                        if ($target) {
                            $query->where('wali.id_wali', $target->id_wali);
                            /*
                            |--------------------------------------------------------------------------
                            | FILTER RELASI SISWA
                            |--------------------------------------------------------------------------
                            | Dibutuhkan karena satu wali bisa mempunyai relasi pada tabel siswa_wali.
                            */
                            if ($target->id_siswa) {
                                $query->where('siswa.id_siswa', $target->id_siswa);
                            }
                        } else {
                            $query->whereRaw('1 = 0'); // Nomor tidak ditemukan
                        }
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | SEARCH TEKS
                    |--------------------------------------------------------------------------
                    | Cari berdasarkan: Nama wali, Hubungan, Nama siswa, Fingerprint ID
                    */
                    $query->where(function ($q) use ($search) {
                        $q->where('wali.nama_wali', 'like', "%{$search}%")
                          ->orWhere('siswa_wali.hubungan', 'like', "%{$search}%")
                          ->orWhere('siswa.nama_siswa', 'like', "%{$search}%")
                          ->orWhere('wali.fingerprint_id', 'like', "%{$search}%");
                    });
                }
            }

            // =================================================
            // PAGINATION SIDIK JARI
            // =================================================
            $data = $query->orderBy('wali.nama_wali', 'asc')->paginate(10)->withQueryString();
            return view('admin.sidik-jari', compact('data', 'tab', 'search'));
        }

        // =================================================
        // TAB TIDAK DITEMUKAN
        // =================================================
        else {
            abort(404);
        }
    }

    // =====================================================
    // HALAMAN TAMBAH RFID
    // =====================================================
    public function create()
    {
        return view('admin.tambah-data-rfid');
    }

    // =====================================================
    // DAFTARKAN RFID
    // =====================================================
    public function registerRfid(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'uid_rfid' => 'required|string',
        ]);

        $uid = strtoupper(trim($request->uid_rfid));

        // =================================================
        // CEK RFID SUDAH DIGUNAKAN
        // =================================================
        $sudahDigunakan = DB::table('siswa')
            ->where('rfid_uid', $uid)
            ->where('id_siswa', '!=', $request->id_siswa)
            ->exists();

        if ($sudahDigunakan) {
            return back()->with('error', 'RFID sudah digunakan oleh siswa lain.');
        }

        // =================================================
        // CEK SISWA AKTIF
        // =================================================
        $siswa = DB::table('siswa')
            ->where('id_siswa', $request->id_siswa)
            ->where('is_active', 1)
            ->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan atau sudah tidak aktif.');
        }

        // =================================================
        // SIMPAN RFID
        // =================================================
        DB::table('siswa')
            ->where('id_siswa', $request->id_siswa)
            ->update(['rfid_uid' => $uid]);

        return back()->with('success', 'RFID berhasil didaftarkan kepada ' . $siswa->nama_siswa . '.');
    }

    // =====================================================
    // DAFTARKAN FINGERPRINT
    // =====================================================
    public function registerFingerprint(Request $request)
    {
        $request->validate([
            'id_wali' => 'required|exists:wali,id_wali',
            'fingerprint_id' => 'required|integer',
        ]);

        $fingerprintId = $request->fingerprint_id;

        // =================================================
        // CEK HASIL ENROLL
        // =================================================
        $log = DB::table('log_tap')
            ->where('fingerprint_id', $fingerprintId)
            ->where('keterangan', 'enroll fingerprint')
            ->latest('created_at')
            ->first();

        if (!$log) {
            return back()->with('error', 'ID fingerprint belum terdeteksi dari proses enroll.');
        }

        // =================================================
        // CEK FINGERPRINT SUDAH DIGUNAKAN
        // =================================================
        $sudahDigunakan = DB::table('wali')
            ->where('fingerprint_id', $fingerprintId)
            ->where('id_wali', '!=', $request->id_wali)
            ->exists();

        if ($sudahDigunakan) {
            return back()->with('error', 'Sidik jari sudah terdaftar pada wali lain.');
        }

        // =================================================
        // CEK WALI AKTIF
        // =================================================
        $wali = DB::table('wali')
            ->where('id_wali', $request->id_wali)
            ->where('is_active', 1)
            ->first();

        if (!$wali) {
            return back()->with('error', 'Data wali tidak ditemukan atau sudah tidak aktif.');
        }

        // =================================================
        // SIMPAN FINGERPRINT
        // =================================================
        DB::table('wali')
            ->where('id_wali', $request->id_wali)
            ->update([
                'fingerprint_id' => $fingerprintId,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Sidik jari berhasil didaftarkan kepada ' . $wali->nama_wali . '.');
    }

    // =====================================================
    // LEPAS RFID / SIDIK JARI
    // =====================================================
    public function destroy($tab, $id)
    {
        // =================================================
        // RFID
        // =================================================
        if ($tab === 'rfid') {
            DB::table('siswa')
                ->where('id_siswa', $id)
                ->update(['rfid_uid' => null]);

            return back()->with('success', 'RFID berhasil dilepas dari siswa.');
        }
        // =================================================
        // SIDIK JARI
        // =================================================
        elseif ($tab === 'sidik-jari') {
            DB::table('wali')
                ->where('id_wali', $id)
                ->update([
                    'fingerprint_id' => null,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Sidik jari berhasil dilepas dari wali.');
        }

        abort(404);
    }

    // =====================================================
    // RFID TERAKHIR
    // =====================================================
    public function latestRFID()
    {
        // =================================================
        // AMBIL RFID TERBARU
        // =================================================
        $latest = DB::table('log_tap')
            ->where('id_device', 1)
            ->whereNotNull('uid_rfid')
            ->orderBy('created_at', 'desc')
            ->first();

        // =================================================
        // BELUM ADA SCAN
        // =================================================
        if (!$latest) {
            return response()->json([
                'uid_rfid' => null,
                'terdaftar' => false,
                'nama_siswa' => null,
            ]);
        }

        // =================================================
        // CARI PEMILIK RFID
        // =================================================
        $siswa = DB::table('siswa')
            ->where('rfid_uid', $latest->uid_rfid)
            ->select('id_siswa', 'nama_siswa', 'rfid_uid')
            ->first();

        return response()->json([
            'uid_rfid' => $latest->uid_rfid,
            'terdaftar' => $siswa ? true : false,
            'nama_siswa' => $siswa->nama_siswa ?? null,
        ]);
    }

    // =====================================================
    // FINGERPRINT TERAKHIR
    // =====================================================
    public function latestFingerprint()
    {
        // =================================================
        // AMBIL FINGERPRINT TERBARU
        // =================================================
        $log = DB::table('log_tap')
            ->whereNotNull('fingerprint_id')
            ->latest('created_at')
            ->first();

        // =================================================
        // BELUM ADA FINGERPRINT
        // =================================================
        if (!$log) {
            return response()->json([
                'fingerprint_id' => null,
                'uid_rfid' => null,
                'terdaftar' => false,
                'nama_wali' => null,
                'id_siswa' => null,
                'nama_siswa' => null,
                'keterangan' => null,
            ]);
        }

        // =================================================
        // CARI WALI
        // =================================================
        $wali = DB::table('wali')
            ->where('fingerprint_id', $log->fingerprint_id)
            ->where('is_active', 1)
            ->select('id_wali', 'nama_wali', 'fingerprint_id')
            ->first();

        $siswa = null;

        // =================================================
        // JIKA DARI ENROLL - CARI SISWA BERDASARKAN RFID
        // =================================================
        if ($log->uid_rfid) {
            $siswa = DB::table('siswa')
                ->where('rfid_uid', $log->uid_rfid)
                ->where('is_active', 1)
                ->select('id_siswa', 'nama_siswa')
                ->first();
        }

        // =================================================
        // JIKA DARI PENJEMPUTAN - CARI SISWA MELALUI SISWA_WALI
        // =================================================
        if (!$siswa && $wali) {
            $siswa = DB::table('siswa_wali')
                ->join('siswa', 'siswa_wali.id_siswa', '=', 'siswa.id_siswa')
                ->where('siswa_wali.id_wali', $wali->id_wali)
                ->select('siswa.id_siswa', 'siswa.nama_siswa')
                ->first();
        }

        // =================================================
        // RESPONSE
        // =================================================
        return response()->json([
            'fingerprint_id' => $log->fingerprint_id,
            'uid_rfid' => $log->uid_rfid,
            'terdaftar' => $wali ? true : false,
            'nama_wali' => $wali->nama_wali ?? null,
            'id_siswa' => $siswa->id_siswa ?? null,
            'nama_siswa' => $siswa->nama_siswa ?? null,
            'keterangan' => $log->keterangan,
        ]);
    }
}
