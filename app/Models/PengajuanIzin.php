<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PengajuanIzin extends Model
{
    protected $table = 'pengajuan_izin';
    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis',       // enum['izin','sakit','cuti']
        'alasan',
        'status',      // enum['pending','disetujui','ditolak']
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Formatted dates for view
    public function getTanggalMulaiFormattedAttribute()
    {
        return $this->tanggal_mulai?->format('d/m/Y');
    }
    public function getTanggalSelesaiFormattedAttribute()
    {
        return $this->tanggal_selesai?->format('d/m/Y');
    }

    // === DASHBOARD SCOPES & AGREGASI ===

    // Scope: filter by month, year, or date range (for dashboard chart/stat)
    public function scopeForMonth($query, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        return $query->whereMonth('tanggal_mulai', $month)
                     ->whereYear('tanggal_mulai', $year);
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereDate('tanggal_mulai', '>=', $from)
                     ->whereDate('tanggal_mulai', '<=', $to);
    }

    // Scope: filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: filter by jenis
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Count all for dashboard widget
    public static function countByStatus($status)
    {
        return static::where('status', $status)->count();
    }

    // Dashboard summary: count by status (pending/disetujui/ditolak) for a month
    public static function summaryByStatus($month = null, $year = null)
    {
        $base = static::forMonth($month, $year);
        return [
            'pending'    => (clone $base)->where('status', 'pending')->count(),
            'disetujui'  => (clone $base)->where('status', 'disetujui')->count(),
            'ditolak'    => (clone $base)->where('status', 'ditolak')->count(),
        ];
    }

    // Dashboard summary: count by jenis
    public static function summaryByJenis($month = null, $year = null)
    {
        $base = static::forMonth($month, $year);
        return [
            'izin'  => (clone $base)->where('jenis', 'izin')->count(),
            'sakit' => (clone $base)->where('jenis', 'sakit')->count(),
            'cuti'  => (clone $base)->where('jenis', 'cuti')->count(),
        ];
    }
}