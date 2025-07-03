<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanIzinRequest;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PengajuanIzinController extends Controller
{
    /**
     * Tampilkan daftar pengajuan izin milik user (atau semua, jika admin/manager).
     */
    public function index(Request $request)
    {
        $title = 'Pengajuan Izin/Sakit/Cuti';
        $keyword = $request->keyword ?? null;

        $query = PengajuanIzin::with('user');

        // Filter untuk karyawan: hanya data sendiri
        if (Auth::user()->role === 'karyawan') {
            $query->where('user_id', Auth::id());
        }

        // Filter keyword (nama user)
        if ($keyword) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }

        $datas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('livewire.pengajuan-izin.index', [
            'title' => $title,
            'datas' => $datas,
            'keyword' => $keyword,
        ]);
    }

    /**
     * Form pengajuan izin baru.
     */
    public function create()
    {
        $title = 'Ajukan Izin/Sakit/Cuti';
        return view('livewire.pengajuan-izin.create', [
            'title' => $title,
        ]);
    }

    /**
     * Simpan pengajuan izin baru.
     */
    public function store(PengajuanIzinRequest $request)
    {
        PengajuanIzin::create([
            'user_id' => Auth::id(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis' => $request->jenis,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuan-izin.index')->with('success', 'Pengajuan berhasil dikirim');
    }

    /**
     * Tampilkan detail pengajuan izin.
     */
    public function show(PengajuanIzin $pengajuan_izin)
    {
        if (request()->expectsJson()) {
            // return beserta relasi user
            return response()->json($pengajuan_izin->load('user'));
        }
        // jika bukan json, redirect atau return view detail
        return redirect()->route('pengajuan-izin.index');
    }

    /**
     * Approve/tolak pengajuan (hanya admin/manager).
     */
    public function update(Request $request, PengajuanIzin $pengajuan_izin)
    {
        // Batasi hanya admin & manager
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak punya akses untuk mengubah status!');
        }

        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);
        $pengajuan_izin->status = $request->status;
        $pengajuan_izin->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'status' => $pengajuan_izin->status]);
        }
        return redirect()->route('pengajuan-izin.index')->with('success', 'Status pengajuan berhasil diubah');
    }
    /**
     * Hapus pengajuan (opsional, misal user ingin membatalkan selama masih pending).
     */
    public function destroy(PengajuanIzin $pengajuan_izin)
    {
        if (
            Auth::user()->id === $pengajuan_izin->user_id
            && $pengajuan_izin->status === 'pending'
        ) {
            $pengajuan_izin->delete();
            return redirect()->route('pengajuan-izin.index')->with('success', 'Pengajuan berhasil dibatalkan');
        }
        abort(403, 'Tidak diizinkan.');
    }

    /**
     * Rekap pengajuan izin (khusus admin/manager).
     */
    public function rekap(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : now()->startOfMonth();
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $query = PengajuanIzin::with('user')
            ->whereBetween('tanggal_mulai', [$from_date, $to_date]);

        if ($keyword) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }

        $datas = $query->orderBy('tanggal_mulai')->get();

        return view('livewire.pengajuan-izin.rekap', [
            'title' => 'Rekap Pengajuan Izin/Sakit/Cuti',
            'datas' => $datas,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'keyword' => $keyword,
        ]);
    }
}