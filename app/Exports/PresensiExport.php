<?php

namespace App\Exports;

use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresensiExport implements FromView, ShouldAutoSize
{
    protected $from_date;
    protected $to_date;
    protected $keyword;

    public function __construct($from_date, $to_date, $keyword)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->keyword = $keyword;
    }

    public function view(): View
    {
        $from_date = Carbon::parse($this->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($this->to_date)->format('Y-m-d');
        $keyword = $this->keyword;

        $presensis = Presensi::with('user');

        if ($this->from_date && $this->to_date) {
            $presensis = $presensis->whereBetween('tanggal', [$from_date, $to_date]);
        }

        if ($this->keyword) {
            $presensis = $presensis->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        $presensis = $presensis->get();

        return view('excel.data-presensi', [
            'presensis' => $presensis,
        ]);
    }
}