<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPenjemputanController extends Controller
{
    public function index(Request $request)
{
    $cari = $request->cari;

    $kelas = DB::table('kelas')
        ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
        ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
        ->select(
            'kelas.id_kelas',
            'kelas.nama_kelas',
            'guru.nama_guru',
            DB::raw('COUNT(siswa.id_siswa) as jumlah_siswa')
        )
        ->groupBy(
            'kelas.id_kelas',
            'kelas.nama_kelas',
            'guru.nama_guru'
        );

    if ($cari) {
        $kelas->where(function ($q) use ($cari) {
            $q->where('kelas.nama_kelas', 'like', "%{$cari}%")
              ->orWhere('guru.nama_guru', 'like', "%{$cari}%");
        });
    }

    $kelas = $kelas->orderBy('kelas.nama_kelas')->get();

    return view('admin.data-penjemputan', compact('kelas'));
}
    public function status(Request $request, $id_kelas)
{
    $tanggal = $request->tanggal ?? date('Y-m-d');

    // Data kelas
    $kelas = DB::table('kelas')
        ->leftJoin('guru','kelas.id_guru','=','guru.id_guru')
        ->select(
            'kelas.id_kelas',
            'kelas.nama_kelas',
            'guru.nama_guru'
        )
        ->where('kelas.id_kelas',$id_kelas)
        ->first();

    // Daftar siswa
        $siswa = DB::table('siswa')

            ->leftJoin('penjemputan', function ($join) use ($tanggal) {
                $join->on('siswa.id_siswa', '=', 'penjemputan.id_siswa')
                    ->whereDate('penjemputan.tanggal', $tanggal);
            })

            ->select(
                'siswa.id_siswa',
                'siswa.nis',
                'siswa.nama_siswa',
                'penjemputan.id',
                'penjemputan.status'
            )

            ->where('siswa.id_kelas', $id_kelas)

            ->orderBy('siswa.nama_siswa')

            ->get();

    return view(
        'admin.status-penjemputan',
        compact(
            'kelas',
            'siswa',
            'tanggal'
        )
    );
}
public function updateStatus(Request $request)
{
    $request->validate([
        'id_siswa' => 'required',
        'tanggal'  => 'required|date',
        'status'   => 'required'
    ]);

    $relasi = DB::table('siswa_wali')
        ->where('id_siswa',$request->id_siswa)
        ->first();

    if(!$relasi){

        return back()->with('error','Relasi siswa dan wali tidak ditemukan.');

    }

    $penjemputan = DB::table('penjemputan')
        ->where('id_siswa',$request->id_siswa)
        ->whereDate('tanggal',$request->tanggal)
        ->first();
    if($penjemputan){
        DB::table('penjemputan')
            ->where('id',$penjemputan->id)
            ->update([
                'status'=>$request->status
            ]);
    }else{
        if($request->status=="Dijemput"){
            DB::table('penjemputan')->insert([
                'id_siswa'=>$request->id_siswa,
                'id_wali'=>$relasi->id_wali,
                'tanggal'=>$request->tanggal,
                'jam_jemput'=>now()->format('H:i:s'),
                'status'=>'Dijemput'
            ]);
        }
    }
    return back()->with('success','Status berhasil diperbarui.');
}
}
