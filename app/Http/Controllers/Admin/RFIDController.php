<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RFIDController extends Controller
{
    // ================= HALAMAN RFID / SIDIK JARI =================
    public function index($tab = 'rfid')
    {
        if ($tab === 'rfid') {

            $data = DB::table('siswa')
                ->select(
                    'id_siswa',
                    'nama_siswa',
                    'rfid_uid'
                )
                ->where('is_active', 1)
                ->paginate(10);

            return view('admin.rfid', compact(
                'data',
                'tab'
            ));

        } elseif ($tab === 'sidik-jari') {
            $data = DB::table('wali')
                ->leftJoin(
                    'siswa_wali',
                    'wali.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                )
                ->leftJoin(
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
                ->where('wali.is_active', 1)
                ->orderBy('wali.nama_wali')
                ->paginate(10);

            return view('admin.sidik-jari', compact(
                'data',
                'tab'
            ));
        } 
        else {

            abort(404);
        }
    }

    public function create()
    {
        return view('admin.tambah-data-rfid');
    }

    // ================= DAFTARKAN RFID =================
    public function registerRfid(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'uid_rfid' => 'required|string',
        ]);

        $uid = strtoupper(trim($request->uid_rfid));

        // Cek apakah RFID sudah digunakan siswa lain
        $sudahDigunakan = DB::table('siswa')
            ->where('rfid_uid', $uid)
            ->where('id_siswa', '!=', $request->id_siswa)
            ->exists();

        if ($sudahDigunakan) {
            return back()->with(
                'error',
                'RFID sudah digunakan oleh siswa lain.'
            );
        }

        // Pastikan siswa aktif
        $siswa = DB::table('siswa')
            ->where('id_siswa', $request->id_siswa)
            ->where('is_active', 1)
            ->first();

        if (!$siswa) {
            return back()->with(
                'error',
                'Data siswa tidak ditemukan atau sudah tidak aktif.'
            );
        }

        // Simpan UID RFID
        DB::table('siswa')
        ->where('id_siswa', $request->id_siswa)
        ->update([
            'rfid_uid' => $uid,
        ]);

        return back()->with(
            'success',
            'RFID berhasil didaftarkan kepada ' . $siswa->nama_siswa . '.'
        );
    }
    // ================= DAFTARKAN FINGERPRINT =================
    public function registerFingerprint(Request $request)
    {
        $request->validate([
            'id_wali' => 'required|exists:wali,id_wali',
            'fingerprint_id' => 'required|integer',
        ]);

        $fingerprintId = $request->fingerprint_id;

        // Pastikan fingerprint berasal dari hasil enroll
        $log = DB::table('log_tap')
            ->where('fingerprint_id', $fingerprintId)
            ->where('keterangan', 'enroll fingerprint')
            ->latest('created_at')
            ->first();

        if (!$log) {
            return back()->with(
                'error',
                'ID fingerprint belum terdeteksi dari proses enroll.'
            );
        }

        // Cek apakah fingerprint dipakai wali lain
        $sudahDigunakan = DB::table('wali')
            ->where('fingerprint_id', $fingerprintId)
            ->where('id_wali', '!=', $request->id_wali)
            ->exists();

        if ($sudahDigunakan) {
            return back()->with(
                'error',
                'Sidik jari sudah terdaftar pada wali lain.'
            );
        }

        // Ambil wali aktif
        $wali = DB::table('wali')
            ->where('id_wali', $request->id_wali)
            ->where('is_active', 1)
            ->first();

        if (!$wali) {
            return back()->with(
                'error',
                'Data wali tidak ditemukan atau sudah tidak aktif.'
            );
        }

        // Simpan fingerprint
        DB::table('wali')
            ->where('id_wali', $request->id_wali)
            ->update([
                'fingerprint_id' => $fingerprintId,
                'updated_at' => now(),
            ]);

        return back()->with(
            'success',
            'Sidik jari berhasil didaftarkan kepada ' .
            $wali->nama_wali . '.'
        );
    }

    // ================= HAPUS =================
    public function destroy($tab, $id)
    {
            if ($tab === 'rfid') {

                DB::table('siswa')
                    ->where('id_siswa', $id)
                    ->update([
                        'rfid_uid' => null,
                    ]);

                return back()->with(
                    'success',
                    'RFID berhasil dilepas dari siswa.'
                );

            } elseif ($tab === 'sidik-jari') {

                DB::table('wali')
                    ->where('id_wali', $id)
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

        public function latestRFID()
        {
            // Ambil scan RFID terbaru
            $latest = DB::table('log_tap')
                ->where('id_device', 1)
                ->whereNotNull('uid_rfid')
                ->orderBy('created_at', 'desc')
                ->first();

            // Belum pernah ada scan
            if (!$latest) {
                return response()->json([
                    'uid_rfid' => null,
                    'terdaftar' => false,
                    'nama_siswa' => null,
                ]);
            }

            // Cek UID sudah dimiliki siswa atau belum
            $siswa = DB::table('siswa')
                ->where('rfid_uid', $latest->uid_rfid)
                ->select(
                    'id_siswa',
                    'nama_siswa',
                    'rfid_uid'
                )
                ->first();

            return response()->json([
                'uid_rfid' => $latest->uid_rfid,
                'terdaftar' => $siswa ? true : false,
                'nama_siswa' => $siswa->nama_siswa ?? null,
            ]);
        }

        public function latestFingerprint()
        {
            // Ambil aktivitas fingerprint terbaru
            // Bisa dari enroll maupun penjemputan
            $log = DB::table('log_tap')
                ->whereNotNull('fingerprint_id')
                ->latest('created_at')
                ->first();

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

            // Cari wali berdasarkan fingerprint
            $wali = DB::table('wali')
                ->where('fingerprint_id', $log->fingerprint_id)
                ->where('is_active', 1)
                ->select(
                    'id_wali',
                    'nama_wali',
                    'fingerprint_id'
                )
                ->first();

            $siswa = null;

            // ==========================================
            // JIKA SCAN DARI ENROLL
            // Cari siswa berdasarkan UID RFID
            // ==========================================
            if ($log->uid_rfid) {

                $siswa = DB::table('siswa')
                    ->where('rfid_uid', $log->uid_rfid)
                    ->where('is_active', 1)
                    ->select(
                        'id_siswa',
                        'nama_siswa'
                    )
                    ->first();
            }

            // ==========================================
            // JIKA SCAN PENJEMPUTAN
            // Cari siswa melalui relasi siswa_wali
            // ==========================================
            if (!$siswa && $wali) {

                $siswa = DB::table('siswa_wali')
                    ->join(
                        'siswa',
                        'siswa_wali.id_siswa',
                        '=',
                        'siswa.id_siswa'
                    )
                    ->where(
                        'siswa_wali.id_wali',
                        $wali->id_wali
                    )
                    ->select(
                        'siswa.id_siswa',
                        'siswa.nama_siswa'
                    )
                    ->first();
            }

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