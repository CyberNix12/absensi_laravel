<x-layouts.app>
<section class="w-full h-full">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <x-page-title title="Rekap Pengajuan Izin/Cuti/Sakit" />

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse border border-neutral-200 dark:border-neutral-700 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-neutral-800">
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">#</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Nama</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Tanggal Mulai</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Tanggal Selesai</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Jenis</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Alasan</th>
                        <th class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekapIzin as $izin)
                        <tr>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ $izin->user->name ?? '-' }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ $izin->tanggal_mulai }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ $izin->tanggal_selesai }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ ucfirst($izin->jenis) }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">{{ $izin->alasan }}</td>
                            <td class="border border-neutral-200 dark:border-neutral-700 py-2 px-4">
                                @if ($izin->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($izin->status === 'disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-error">Ditolak</span>
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
</x-layouts.app>
