<?php

namespace App\Http\Controllers;

use App\Exports\PresensiExport;
use App\Models\Presensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Presensi';

        $from_date = $request->from_date ?? now()->startOfMonth();
        $to_date = $request->to_date ?? now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $data = Presensi::with('user');

        if ($keyword) {
            $data = $data->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%');
            });
        }

        if ($from_date && $to_date) {
            $data = $data->whereBetween('created_at', [$from_date, $to_date]);
        }

        if (Auth::user()->role == 'karyawan') {
            $data = $data->where('user_id', Auth::id());
        }

        $data = $data->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $jam_masuk = null;
        $jam_pulang = null;
        $data_presensi = Presensi::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
            ->first();

        if ($data_presensi) {
            $jam_masuk = $data_presensi->jam_masuk ? Carbon::parse($data_presensi->jam_masuk)->format('H:i:s') : null;
            $jam_pulang = $data_presensi->jam_pulang ? Carbon::parse($data_presensi->jam_pulang)->format('H:i:s') : null;
        }

        return view('pages.data-presensi', [
            'title' => $title,
            'datas' => $data,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'keyword' => $keyword,
            'jam_masuk' => $jam_masuk,
            'jam_pulang' => $jam_pulang,
        ]);
    }

    public function rekap(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : now()->startOfMonth();
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : now()->endOfMonth();
        $keyword = $request->keyword ?? null;

        $file_name = $from_date->format('Y-m-d').'-'.$to_date->format('Y-m-d').'-Presensi-.xlsx';

        return Excel::download(new PresensiExport($from_date, $to_date, $keyword), $file_name);
    }

    public function in()
    {
        $title = 'Presensi Masuk';

        return view('pages.data-presensi-karyawan-in', [
            'title' => $title,
        ]);
    }

    public function in_store(Request $request)
    {
        try {
            $request->validate([
                'location' => ['required', 'string'],
                'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            $check = Presensi::where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
                ->first();

            if ($check) {
                if ($check->jam_masuk) {
                    return redirect()->route('data-presensi.in')->withErrors('Anda sudah presensi masuk hari ini');
                }
            }

            Presensi::create([
                'user_id' => Auth::id(),
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'lokasi_masuk' => $request->location,
                'foto_masuk' => $request->file('foto')->store('presensi', 'public'),
            ]);

            return redirect()->route('data-presensi')->with('success', 'Presensi masuk berhasil');
        } catch (Exception $e) {
            return redirect()->route('data-presensi.in')->withErrors('Presensi masuk gagal: '.$e->getMessage());
        }
    }

    public function out()
    {
        $title = 'Presensi Pulang';

        return view('pages.data-presensi-karyawan-out', [
            'title' => $title,
        ]);
    }

    public function out_store(Request $request)
    {
        try {
            $request->validate([
                'location' => ['required', 'string'],
                'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            $check = Presensi::where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::now()->format('Y-m-d'))
                ->first();

            if (! $check) {
                return redirect()->route('data-presensi.out')->withErrors('Anda belum presensi masuk hari ini');
            }

            if (! $check->jam_masuk) {
                return redirect()->route('data-presensi.out')->withErrors('Anda belum presensi masuk hari ini');
            } elseif ($check->jam_pulang) {
                return redirect()->route('data-presensi.out')->withErrors('Anda sudah presensi pulang hari ini');
            }

            $check->update([
                'jam_pulang' => Carbon::now()->format('H:i:s'),
                'lokasi_pulang' => $request->location,
                'foto_pulang' => $request->file('foto')->store('presensi', 'public'),
            ]);

            return redirect()->route('data-presensi')->with('success', 'Presensi pulang berhasil');
        } catch (Exception $e) {
            return redirect()->route('data-presensi.in')->withErrors('Presensi pulang gagal: '.$e->getMessage());
        }
    }
}