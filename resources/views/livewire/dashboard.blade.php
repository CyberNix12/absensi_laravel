<?php

use App\Models\Presensi;
use App\Models\User;
use App\Models\PengajuanIzin;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Title};
use Illuminate\Support\Collection;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    public $title = 'DASHBOARD';
    public $total_kehadiran = 0;
    public $total_karyawan = 0;
    public $rekap_izin = 0;
    public $rekap_sakit = 0;
    public $rekap_cuti = 0;

    // hanya untuk karyawan
    public $presensi_hari_ini;
    public $jam_masuk;
    public $jam_pulang;

    // hanya untuk admin/manager
    public $data_rekap_kehadiran;
    public $daftar_masuk_hari_ini;
    public $daftar_pulang_hari_ini;

    // FILTER RIWAYAT PRESENSI
    public $filter_tanggal;
    public $data_presensi_karyawan;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->total_kehadiran = $this->hitungTotalKehadiran();
            $this->total_karyawan = $this->hitungKaryawan();
            $this->rekap_izin = $this->hitungRekapIzin('izin');
            $this->rekap_sakit = $this->hitungRekapIzin('sakit');
            $this->rekap_cuti = $this->hitungRekapIzin('cuti');
            $this->data_rekap_kehadiran = $this->getDataRekapKehadiran();
            $this->daftar_masuk_hari_ini = $this->getDaftarMasukHariIni();
            $this->daftar_pulang_hari_ini = $this->getDaftarPulangHariIni();
        } elseif ($user->role === 'manager') {
            $this->total_karyawan = $this->hitungKaryawan();
            $this->rekap_izin = $this->hitungRekapIzin('izin');
            $this->rekap_sakit = $this->hitungRekapIzin('sakit');
            $this->rekap_cuti = $this->hitungRekapIzin('cuti');
            $this->data_rekap_kehadiran = $this->getDataRekapKehadiran();
            $this->daftar_masuk_hari_ini = $this->getDaftarMasukHariIni();
            $this->daftar_pulang_hari_ini = $this->getDaftarPulangHariIni();
        } elseif ($user->role === 'karyawan') {
            $this->presensi_hari_ini = $this->getPresensiHariIni();
            $this->jam_masuk = $this->presensi_hari_ini ? $this->presensi_hari_ini->jam_masuk : null;
            $this->jam_pulang = $this->presensi_hari_ini ? $this->presensi_hari_ini->jam_pulang : null;
            // Set filter awal hari ini
            $this->filter_tanggal = now()->format('Y-m-d');
            $this->data_presensi_karyawan = $this->getDataPresensiKaryawan($this->filter_tanggal);
        }
    }

    // Agar otomatis update saat filter_tanggal berubah
    public function updatedFilterTanggal($value)
    {
        $this->data_presensi_karyawan = $this->getDataPresensiKaryawan($value);
    }

    // ADMIN & MANAGER
    #[Computed]
    public function hitungTotalKehadiran(): int
    {
        return Presensi::whereDate('tanggal', now())->count();
    }

    #[Computed]
    public function hitungKaryawan(): int
    {
        return User::where('role', 'karyawan')->count();
    }

    #[Computed]
    public function hitungRekapIzin($jenis): int
    {
        return PengajuanIzin::where('jenis', $jenis)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal_mulai', now()->format('m'))
            ->whereYear('tanggal_mulai', now()->format('Y'))
            ->count();
    }

    #[Computed]
    public function getDataRekapKehadiran(): Collection
    {
        return User::where('role', 'karyawan')
            ->withCount(['presensi as hadir' => function ($q) {
                $q->whereDate('tanggal', now());
            }])
            ->get();
    }

    #[Computed]
    public function getDaftarMasukHariIni(): Collection
    {
        return Presensi::whereDate('tanggal', now())
            ->whereNotNull('jam_masuk')
            ->with('user:id,name')
            ->orderBy('jam_masuk')
            ->get();
    }

    #[Computed]
    public function getDaftarPulangHariIni(): Collection
    {
        return Presensi::whereDate('tanggal', now())
            ->whereNotNull('jam_pulang')
            ->with('user:id,name')
            ->orderBy('jam_pulang')
            ->get();
    }

    #[Computed]
    public function getPresensiHariIni()
    {
        return Presensi::where('user_id', Auth::id())
            ->whereDate('tanggal', now())
            ->first();
    }

    // Ini yang dipakai untuk filter riwayat
    #[Computed]
    public function getDataPresensiKaryawan($tanggal = null): Collection
    {
        $tanggal = $tanggal ?: now()->format('Y-m-d');
        return Presensi::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();
    }
};
?>
<section class="w-full h-full">
    <div class="">
        <x-page-title :title="$title" class="mb-2" />

        {{-- === ADMIN DASHBOARD === --}}
        @if(auth()->user()->role == 'admin')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-cards.counter variant="primary" title="Total Kehadiran" total="{{ $total_kehadiran }}" icon="fas fa-user-check" />
                <x-cards.counter variant="accent" title="Total Karyawan" total="{{ $total_karyawan }}" icon="fas fa-users" />
                <x-cards.counter variant="success" title="Rekap Izin" total="{{ $rekap_izin }}" icon="fas fa-user-clock" />
                <x-cards.counter variant="danger" title="Rekap Sakit" total="{{ $rekap_sakit }}" icon="fas fa-user-injured" />
                <x-cards.counter variant="info" title="Rekap Cuti" total="{{ $rekap_cuti }}" icon="fas fa-user-times" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-sign-in-alt text-primary-500"></i> Daftar Masuk Hari Ini
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-blue-100 via-white to-purple-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-purple-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Jam Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @forelse ($daftar_masuk_hari_ini as $masuk)
                                <tr class="hover:bg-blue-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $masuk->user->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $masuk->jam_masuk ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-neutral-400 py-2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-cards.base-card>
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-sign-out-alt text-danger-500"></i> Daftar Pulang Hari Ini
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-purple-100 via-white to-blue-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-blue-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @forelse ($daftar_pulang_hari_ini as $pulang)
                                <tr class="hover:bg-purple-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $pulang->user->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $pulang->jam_pulang ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-neutral-400 py-2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-cards.base-card>
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-bar text-accent-500"></i> Rekap Kehadiran Karyawan
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-green-100 via-white to-blue-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-blue-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @foreach ($data_rekap_kehadiran as $data)
                                <tr class="hover:bg-green-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $data->name }}</td>
                                    <td class="py-2 px-4 text-center">
                                        @if($data->hadir > 0)
                                            <span class="inline-block rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold">
                                                <i class="fas fa-check-circle"></i> Hadir
                                            </span>
                                        @else
                                            <span class="inline-block rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold">
                                                <i class="fas fa-times-circle"></i> Tidak Hadir
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.base-card>
            </div>
        @endif

        {{-- === MANAGER DASHBOARD === --}}
        @if(auth()->user()->role == 'manager')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-cards.counter variant="accent" title="Total Karyawan" total="{{ $total_karyawan }}" icon="fas fa-users" />
                <x-cards.counter variant="success" title="Rekap Izin" total="{{ $rekap_izin }}" icon="fas fa-user-clock" />
                <x-cards.counter variant="danger" title="Rekap Sakit" total="{{ $rekap_sakit }}" icon="fas fa-user-injured" />
                <x-cards.counter variant="info" title="Rekap Cuti" total="{{ $rekap_cuti }}" icon="fas fa-user-times" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-sign-in-alt text-primary-500"></i> Daftar Masuk Hari Ini
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-blue-100 via-white to-purple-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-purple-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Jam Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @forelse ($daftar_masuk_hari_ini as $masuk)
                                <tr class="hover:bg-blue-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $masuk->user->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $masuk->jam_masuk ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-neutral-400 py-2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-cards.base-card>
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-sign-out-alt text-danger-500"></i> Daftar Pulang Hari Ini
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-purple-100 via-white to-blue-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-blue-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @forelse ($daftar_pulang_hari_ini as $pulang)
                                <tr class="hover:bg-purple-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $pulang->user->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $pulang->jam_pulang ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-neutral-400 py-2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-cards.base-card>
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 text-neutral-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-bar text-accent-500"></i> Rekap Kehadiran Karyawan
                    </h1>
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-green-100 via-white to-blue-100 dark:from-neutral-800 dark:via-neutral-900 dark:to-blue-900">
                            <tr>
                                <th class="py-3 px-4 text-left">#</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-900">
                            @foreach ($data_rekap_kehadiran as $data)
                                <tr class="hover:bg-green-50 dark:hover:bg-neutral-800 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $data->name }}</td>
                                    <td class="py-2 px-4 text-center">
                                        @if($data->hadir > 0)
                                            <span class="inline-block rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold">
                                                <i class="fas fa-check-circle"></i> Hadir
                                            </span>
                                        @else
                                            <span class="inline-block rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold">
                                                <i class="fas fa-times-circle"></i> Tidak Hadir
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.base-card>
            </div>
        @endif

        {{-- === KARYAWAN DASHBOARD === --}}
        @if(auth()->user()->role == 'karyawan')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-calendar-day text-primary-500"></i> Presensi Hari Ini
                    </h1>
                    <div class="flex flex-col gap-2 text-md">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">Tanggal:</span>
                            <span>{{ now()->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">Jam Masuk:</span>
                            <span>{{ $jam_masuk ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">Jam Pulang:</span>
                            <span>{{ $jam_pulang ?? '-' }}</span>
                        </div>
                    </div>
                </x-cards.base-card>
                <x-cards.base-card>
                    <h1 class="text-lg font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-accent-500"></i> Riwayat Presensi
                    </h1>
                    
                    <table class="w-full table-auto border-collapse rounded-xl shadow overflow-hidden text-sm table-modern">
                        <thead class="bg-gradient-to-r from-blue-100 via-white to-green-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Tanggal</th>
                                <th class="py-3 px-4 text-left">Jam Masuk</th>
                                <th class="py-3 px-4 text-left">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_presensi_karyawan as $presensi)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-2 px-4">{{ $presensi->tanggal }}</td>
                                    <td class="py-2 px-4">{{ $presensi->jam_masuk ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $presensi->jam_pulang ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-neutral-400 py-2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-cards.base-card>
            </div>
        @endif
    </div>
</section>