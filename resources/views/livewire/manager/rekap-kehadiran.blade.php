@php
    // Pastikan variabel $rekapPresensi adalah array/collection daftar presensi seluruh karyawan/bawahan yang ingin direkap
@endphp

<section class="w-full h-full">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <x-page-title title="Rekap Kehadiran Karyawan" />

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse border border-neutral-200 dark:border-neutral-700 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-neutral-800">
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">#</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-left">Nama</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">Tanggal</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">Jam Masuk</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">Jam Pulang</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">Lama Kerja</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekapPresensi as $rekap)
                        <tr>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ $rekap->user->name ?? '-' }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $rekap->tanggal }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $rekap->jam_masuk ?? '-' }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $rekap->jam_pulang ?? '-' }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $rekap->lama_kerja ?? '-' }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">
                                @if ($rekap->jam_masuk && $rekap->jam_pulang)
                                    <span class="badge badge-success">Lengkap</span>
                                @elseif ($rekap->jam_masuk)
                                    <span class="badge badge-warning">Belum Pulang</span>
                                @else
                                    <span class="badge badge-error">Belum Presensi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 text-center text-neutral-400">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>