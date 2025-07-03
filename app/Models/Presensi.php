<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Presensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lokasi_masuk',
        'lokasi_pulang',
        'foto_masuk',
        'foto_pulang',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $appends = [
        'foto_masuk_asset',
        'foto_pulang_asset',
        'lama_kerja',
        'lokasi_masuk_gmap',
        'lokasi_pulang_gmap',
    ];

    public function getTanggalAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : null;
    }

    public function getJamMasukAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    public function getJamPulangAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    public function getLamaKerjaAttribute()
    {
        if ($this->jam_masuk && $this->jam_pulang) {
            $masuk = Carbon::createFromFormat('H:i', $this->jam_masuk);
            $pulang = Carbon::createFromFormat('H:i', $this->jam_pulang);

            $diff = $masuk->diff($pulang);

            return $diff->h.' Jam '.$diff->i.' Menit';
        }
    }

    public function getLokasiMasukGmapAttribute()
    {
        return $this->lokasi_masuk ? 'https://maps.google.com/maps?q='.$this->lokasi_masuk : null;
    }

    public function getLokasiPulangGmapAttribute()
    {
        return $this->lokasi_pulang ? 'https://maps.google.com/maps?q='.$this->lokasi_pulang : null;
    }

    public function getFotoMasukAssetAttribute()
    {
        return $this->foto_masuk ? asset('storage/'.$this->foto_masuk) : null;
    }

    public function getFotoPulangAssetAttribute()
    {
        return $this->foto_pulang ? asset('storage/'.$this->foto_pulang) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', now()->format('Y-m-d'));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', now()->format('m'))
            ->whereYear('tanggal', now()->format('Y'));
    }
}