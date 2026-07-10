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

            $baseQuery = DB::table('siswa')
                ->select(
                    'id_siswa',
                    'nama_siswa',
                    'rfid_uid'
                )
                ->where('is_active', 1);


            $query = clone $baseQuery;


            // =================================================
            // SEARCH RFID
            // =================================================
            if ($search !== '') {

                if (ctype_digit($search)) {

                    $nomor = (int) $search;

                    if ($nomor > 0) {

                        $target = (clone $baseQuery)
                            ->orderBy('nama_siswa', 'asc')
                            ->offset($nomor - 1)
                            ->limit(1)
                            ->first();


                        if ($target) {

                            $query->where(
                                'id_siswa',
                                $target->id_siswa
                            );

                        } else {

                            $query->whereRaw('1 = 0');
                        }

                    } else {

                        $query->whereRaw('1 = 0');
                    }

                } else {

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'nama_siswa',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'rfid_uid',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            }


            // =================================================
            // PAGINATION RFID
            // =================================================
            $data = $query
                ->orderBy('nama_siswa', 'asc')
                ->paginate(10)
                ->withQueryString();


            return view(
                'admin.rfid',
                compact(
                    'data',
                    'tab',
                    'search'
                )
            );
        }


        // =================================================
        // TAB SIDIK JARI
        // =================================================
        elseif ($tab === 'sidik-jari') {


            // =================================================
            // AMBIL PROSES ENROLL TERAKHIR
            // =================================================
            $latestEnroll = DB::table('log_tap')
                ->where('keterangan', 'enroll fingerprint')
                ->whereNotNull('fingerprint_id')
                ->whereNotNull('uid_rfid')
                ->orderBy('created_at', 'desc')
                ->first();


            $filterIdSiswa = null;


            // =================================================
            // CEK STATUS ENROLL TERAKHIR
            // =================================================
            if ($latestEnroll) {

                // Apakah fingerprint hasil enroll sudah
                // didaftarkan ke salah satu wali?
                $sudahTerdaftar = DB::table('wali')
                    ->where(
                        'fingerprint_id',
                        $latestEnroll->fingerprint_id
                    )
                    ->exists();


                // Jika BELUM terdaftar:
                // cari siswa dari UID RFID hasil enroll
                if (!$sudahTerdaftar) {

                    $siswaEnroll = DB::table('siswa')
                        ->where(
                            'rfid_uid',
                            $latestEnroll->uid_rfid
                        )
                        ->where('is_active', 1)
                        ->select(
                            'id_siswa',
                            'nama_siswa'
                        )
                        ->first();


                    if ($siswaEnroll) {

                        $filterIdSiswa =
                            $siswaEnroll->id_siswa;
                    }
                }
            }


            // =================================================
            // QUERY DASAR WALI
            // =================================================
            $baseQuery = DB::table('wali')

                ->join(
                    'siswa_wali',
                    'wali.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                )

                ->join(
                    'siswa',
                    'siswa_wali.id_siswa',
                    '=',
                    'siswa.id_siswa'
                )

                ->select(
                    'wali.id_wali',
                    'wali.nama_wali',
                    'wali.fingerprint_id',

                    'siswa_wali.hubungan',

                    'siswa.id_siswa',
                    'siswa.nama_siswa'
                )

                ->where(
                    'wali.is_active',
                    1
                )

                ->where(
                    'siswa.is_active',
                    1
                );


            // =================================================
            // FILTER WALI SISWA SAAT PROSES ENROLL
            // =================================================
            if ($filterIdSiswa !== null) {

                $baseQuery->where(
                    'siswa_wali.id_siswa',
                    $filterIdSiswa
                );
            }


            $query = clone $baseQuery;


            // =================================================
            // SEARCH SIDIK JARI
            // =================================================
            if ($search !== '') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'wali.nama_wali',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'siswa_wali.hubungan',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'siswa.nama_siswa',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'wali.fingerprint_id',
                        'like',
                        "%{$search}%"
                    );
                });
            }


            // =================================================
            // PAGINATION
            // =================================================
            $data = $query
                ->orderBy(
                    'wali.nama_wali',
                    'asc'
                )
                ->paginate(10)
                ->withQueryString();


            return view(
                'admin.sidik-jari',
                compact(
                    'data',
                    'tab',
                    'search'
                )
            );
        }


        // =================================================
        // TAB TIDAK DITEMUKAN
        // =================================================
        abort(404);
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


        $uid = strtoupper(
            trim($request->uid_rfid)
        );


        // =================================================
        // CEK RFID DIGUNAKAN SISWA LAIN
        // =================================================
        $sudahDigunakan = DB::table('siswa')

            ->where(
                'rfid_uid',
                $uid
            )

            ->where(
                'id_siswa',
                '!=',
                $request->id_siswa
            )

            ->exists();


        if ($sudahDigunakan) {

            return back()->with(
                'error',
                'RFID sudah digunakan oleh siswa lain.'
            );
        }


        // =================================================
        // CEK SISWA
        // =================================================
        $siswa = DB::table('siswa')

            ->where(
                'id_siswa',
                $request->id_siswa
            )

            ->where(
                'is_active',
                1
            )

            ->first();


        if (!$siswa) {

            return back()->with(
                'error',
                'Data siswa tidak ditemukan atau tidak aktif.'
            );
        }


        // =================================================
        // SIMPAN RFID
        // =================================================
        DB::table('siswa')

            ->where(
                'id_siswa',
                $request->id_siswa
            )

            ->update([
                'rfid_uid' => $uid
            ]);


        return back()->with(
            'success',
            'RFID berhasil didaftarkan kepada ' .
            $siswa->nama_siswa . '.'
        );
    }



    // =====================================================
    // DAFTARKAN FINGERPRINT
    // =====================================================
    public function registerFingerprint(Request $request)
    {
        $request->validate([
            'id_wali' =>
                'required|exists:wali,id_wali',

            'fingerprint_id' =>
                'required|integer',
        ]);


        $fingerprintId =
            (int) $request->fingerprint_id;


        // =================================================
        // CEK HASIL ENROLL
        // =================================================
        $log = DB::table('log_tap')

            ->where(
                'fingerprint_id',
                $fingerprintId
            )

            ->where(
                'keterangan',
                'enroll fingerprint'
            )

            ->latest('created_at')

            ->first();


        if (!$log) {

            return back()->with(
                'error',
                'ID fingerprint belum terdeteksi dari proses enroll.'
            );
        }


        // =================================================
        // CARI SISWA DARI UID ENROLL
        // =================================================
        $siswaEnroll = DB::table('siswa')

            ->where(
                'rfid_uid',
                $log->uid_rfid
            )

            ->where(
                'is_active',
                1
            )

            ->first();


        if (!$siswaEnroll) {

            return back()->with(
                'error',
                'Siswa dari kartu RFID tidak ditemukan.'
            );
        }


        // =================================================
        // PASTIKAN WALI BERELASI DENGAN SISWA
        // =================================================
        $relasiValid = DB::table('siswa_wali')

            ->where(
                'id_siswa',
                $siswaEnroll->id_siswa
            )

            ->where(
                'id_wali',
                $request->id_wali
            )

            ->exists();


        if (!$relasiValid) {

            return back()->with(
                'error',
                'Wali yang dipilih bukan wali dari siswa tersebut.'
            );
        }


        // =================================================
        // CEK FINGERPRINT DIGUNAKAN WALI LAIN
        // =================================================
        $sudahDigunakan = DB::table('wali')

            ->where(
                'fingerprint_id',
                $fingerprintId
            )

            ->where(
                'id_wali',
                '!=',
                $request->id_wali
            )

            ->exists();


        if ($sudahDigunakan) {

            return back()->with(
                'error',
                'Sidik jari sudah digunakan wali lain.'
            );
        }


        // =================================================
        // CEK WALI
        // =================================================
        $wali = DB::table('wali')

            ->where(
                'id_wali',
                $request->id_wali
            )

            ->where(
                'is_active',
                1
            )

            ->first();


        if (!$wali) {

            return back()->with(
                'error',
                'Data wali tidak ditemukan atau tidak aktif.'
            );
        }


        // =================================================
        // SIMPAN FINGERPRINT
        // =================================================
        DB::table('wali')

            ->where(
                'id_wali',
                $request->id_wali
            )

            ->update([
                'fingerprint_id' =>
                    $fingerprintId,

                'updated_at' =>
                    now(),
            ]);


        // =================================================
        // SETELAH DAFTAR:
        // fingerprint sudah ada di wali
        // sehingga index() otomatis menampilkan SEMUA wali
        // =================================================
        return redirect()
            ->route(
                'iot.index',
                ['tab' => 'sidik-jari']
            )
            ->with(
                'success',
                'Sidik jari berhasil didaftarkan kepada ' .
                $wali->nama_wali . '.'
            );
    }



    // =====================================================
    // LEPAS RFID / SIDIK JARI
    // =====================================================
    public function destroy($tab, $id)
    {
        // =================================================
        // LEPAS RFID
        // =================================================
        if ($tab === 'rfid') {

            DB::table('siswa')

                ->where(
                    'id_siswa',
                    $id
                )

                ->update([
                    'rfid_uid' => null
                ]);


            return back()->with(
                'success',
                'RFID berhasil dilepas dari siswa.'
            );
        }


        // =================================================
        // LEPAS FINGERPRINT
        // =================================================
        elseif ($tab === 'sidik-jari') {

            DB::table('wali')

                ->where(
                    'id_wali',
                    $id
                )

                ->update([
                    'fingerprint_id' => null,
                    'updated_at' => now(),
                ]);


            return back()->with(
                'success',
                'Sidik jari berhasil dilepas dari wali.'
            );
        }


        abort(404);
    }



    // =====================================================
    // RFID TERAKHIR
    // =====================================================
    public function latestRFID()
    {
        $latest = DB::table('log_tap')

            ->where(
                'id_device',
                1
            )

            ->whereNotNull(
                'uid_rfid'
            )

            ->orderBy(
                'created_at',
                'desc'
            )

            ->first();


        if (!$latest) {

            return response()->json([
                'uid_rfid' => null,
                'terdaftar' => false,
                'nama_siswa' => null,
            ]);
        }


        $siswa = DB::table('siswa')

            ->where(
                'rfid_uid',
                $latest->uid_rfid
            )

            ->select(
                'id_siswa',
                'nama_siswa',
                'rfid_uid'
            )

            ->first();


        return response()->json([
            'uid_rfid' =>
                $latest->uid_rfid,

            'terdaftar' =>
                $siswa ? true : false,

            'nama_siswa' =>
                $siswa->nama_siswa ?? null,
        ]);
    }



    // =====================================================
    // FINGERPRINT TERAKHIR
    // =====================================================
    public function latestFingerprint()
    {
        // Hanya ambil hasil ENROLL.
        // Jangan ambil scan penjemputan.
        $log = DB::table('log_tap')

            ->where(
                'keterangan',
                'enroll fingerprint'
            )

            ->whereNotNull(
                'fingerprint_id'
            )

            ->whereNotNull(
                'uid_rfid'
            )

            ->latest(
                'created_at'
            )

            ->first();


        // =================================================
        // BELUM ADA ENROLL
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
        // CARI SISWA DARI UID RFID ENROLL
        // =================================================
        $siswa = DB::table('siswa')

            ->where(
                'rfid_uid',
                $log->uid_rfid
            )

            ->where(
                'is_active',
                1
            )

            ->select(
                'id_siswa',
                'nama_siswa'
            )

            ->first();


        // =================================================
        // CARI WALI PEMILIK FINGERPRINT
        // =================================================
        $wali = DB::table('wali')

            ->where(
                'fingerprint_id',
                $log->fingerprint_id
            )

            ->where(
                'is_active',
                1
            )

            ->select(
                'id_wali',
                'nama_wali',
                'fingerprint_id'
            )

            ->first();


        // =================================================
        // RESPONSE
        // =================================================
        return response()->json([

            'fingerprint_id' =>
                $log->fingerprint_id,

            'uid_rfid' =>
                $log->uid_rfid,

            'terdaftar' =>
                $wali ? true : false,

            'nama_wali' =>
                $wali->nama_wali ?? null,

            'id_siswa' =>
                $siswa->id_siswa ?? null,

            'nama_siswa' =>
                $siswa->nama_siswa ?? null,

            'keterangan' =>
                $log->keterangan,
        ]);
    }
}