<x-layouts.app>
    <section class="w-full h-full">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <x-page-title title="Daftar Pengajuan Izin/Cuti/Sakit" />

            <div class="mb-2">
                <span class="font-semibold">Nama:</span>
                <span>{{ auth()->user()->name }}</span>
            </div>

            @if(auth()->user()->role === 'karyawan')
            <div class="mb-4">
                <a href="{{ route('pengajuan-izin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajukan Izin/Cuti/Sakit
                </a>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-separate border-spacing-0 rounded-xl shadow-sm bg-white dark:bg-neutral-900 text-sm">
                    <thead>
                        <tr>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 text-center font-semibold text-neutral-600 dark:text-neutral-200">#</th>
                            @if(auth()->user()->role !== 'karyawan')
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Nama</th>
                            @endif
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Tanggal Mulai</th>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Tanggal Selesai</th>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Jenis</th>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Alasan</th>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 font-semibold text-neutral-600 dark:text-neutral-200">Status</th>
                            <th class="sticky top-0 z-10 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700 py-3 px-4 text-center font-semibold text-neutral-600 dark:text-neutral-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($datas as $izin)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4 text-center font-medium">{{ $loop->iteration }}</td>
                                @if(auth()->user()->role !== 'karyawan')
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">{{ $izin->user->name ?? '-' }}</td>
                                @endif
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">{{ $izin->tanggal_mulai }}</td>
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">{{ $izin->tanggal_selesai }}</td>
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold uppercase
                                        @if($izin->jenis == 'izin') bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($izin->jenis == 'sakit') bg-orange-50 text-orange-600 dark:bg-orange-900/30 dark:text-orange-300
                                        @else bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-300 @endif">
                                        {{ ucfirst($izin->jenis) }}
                                    </span>
                                </td>
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">{{ $izin->alasan }}</td>
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                                            <select
                                                class="status-select min-w-[125px] appearance-none bg-transparent border-none px-2 py-1 rounded text-xs font-semibold transition
                                                    focus:ring-2 focus:ring-primary-500 focus:outline-none
                                                    @if($izin->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-800/40 dark:text-yellow-200
                                                    @elseif($izin->status == 'disetujui') bg-green-100 text-green-700 dark:bg-green-800/40 dark:text-green-200
                                                    @else bg-red-100 text-red-700 dark:bg-red-800/40 dark:text-red-200 @endif"
                                                style="padding-right:2.5rem"
                                                onchange="updateStatusInline({{ $izin->id }}, this.value, this)"
                                                aria-label="Ubah Status">
                                                <option value="pending" {{ $izin->status == 'pending' ? 'selected' : '' }}>🕓 Pending</option>
                                                <option value="disetujui" {{ $izin->status == 'disetujui' ? 'selected' : '' }}>✔️ Disetujui</option>
                                                <option value="ditolak" {{ $izin->status == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                            </select>
                                            <span class="inline-block" id="status-spinner-{{ $izin->id }}" style="display:none;">
                                                <svg class="animate-spin w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                                </svg>
                                            </span>
                                        @else
                                            {{-- Untuk karyawan: hanya tampilkan badge status --}}
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold
                                                @if($izin->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-800/40 dark:text-yellow-200
                                                @elseif($izin->status == 'disetujui') bg-green-100 text-green-700 dark:bg-green-800/40 dark:text-green-200
                                                @else bg-red-100 text-red-700 dark:bg-red-800/40 dark:text-red-200 @endif">
                                                @if ($izin->status == 'pending') 🕓 Pending
                                                @elseif ($izin->status == 'disetujui') ✔️ Disetujui
                                                @else ❌ Ditolak
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="border-b border-neutral-100 dark:border-neutral-800 py-3 px-4 text-center">
                                    <button type="button" class="btn btn-xs btn-info rounded shadow"
                                            onclick="showIzinModal({{ $izin->id }})">
                                        <svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    </button>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                                        @if ($izin->status === 'pending')
                                            <form action="{{ route('pengajuan-izin.destroy', $izin->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger ml-1 rounded shadow"
                                                    onclick="return confirm('Yakin hapus pengajuan ini?')">
                                                    <svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role !== 'karyawan' ? 8 : 7 }}" class="py-3 text-center text-neutral-400">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Detail Izin --}}
        <div id="izin-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center transition-all">
            <div class="bg-white dark:bg-neutral-900 rounded-xl w-full max-w-lg p-6 relative shadow-2xl border border-neutral-200 dark:border-neutral-800 animate-fade-in">
                <button class="absolute top-3 right-3 btn btn-sm btn-ghost" onclick="closeIzinModal()" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h3 class="text-xl font-bold mb-4 text-neutral-700 dark:text-neutral-200">Detail Pengajuan</h3>
                <div id="izin-modal-content" class="space-y-3 text-base">
                    <!-- Detail akan diisi oleh JS -->
                </div>
            </div>
        </div>

        <style>
            .animate-fade-in { animation: fadeInModal 0.25s; }
            @keyframes fadeInModal { from {transform:scale(0.95); opacity:0;} to {transform:scale(1); opacity:1;} }
            .status-select:focus { outline: 2px solid #6366f1; border-color: #6366f1; }
        </style>
        <script>
            function updateStatusInline(id, status, selectEl) {
                const spinner = document.getElementById('status-spinner-' + id);
                spinner.style.display = 'inline-block';
                selectEl.disabled = true;
                fetch('/pengajuan-izin/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        '_method': 'PUT', // atau 'PATCH'
                        'status': status
                    })
                })
                .then(resp => {
                    spinner.style.display = 'none';
                    selectEl.disabled = false;
                    if (!resp.ok) throw new Error('Gagal update status');
                    // Optionally show a toast
                })
                .catch(err => {
                    spinner.style.display = 'none';
                    selectEl.disabled = false;
                    alert('Gagal update status!');
                });
            }

            function showIzinModal(id) {
                fetch('/pengajuan-izin/' + id, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Data tidak ditemukan');
                    return response.json();
                })
                .then(data => {
                    let statusColor = data.status === 'pending'
                        ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-800/40 dark:text-yellow-200'
                        : (data.status === 'disetujui'
                            ? 'bg-green-100 text-green-700 dark:bg-green-800/40 dark:text-green-200'
                            : 'bg-red-100 text-red-700 dark:bg-red-800/40 dark:text-red-200');
                    let statusText = data.status === 'pending'
                        ? 'Pending'
                        : (data.status === 'disetujui' ? 'Disetujui' : 'Ditolak');
                    let html = `
                        <div class="flex flex-col gap-2">
                            <div><span class="font-semibold">Nama:</span> ${data.user?.name ?? '-'}</div>
                            <div><span class="font-semibold">Tanggal Mulai:</span> ${data.tanggal_mulai}</div>
                            <div><span class="font-semibold">Tanggal Selesai:</span> ${data.tanggal_selesai}</div>
                            <div><span class="font-semibold">Jenis:</span> <span class="uppercase font-semibold">${data.jenis}</span></div>
                            <div><span class="font-semibold">Alasan:</span> <span class="italic">${data.alasan}</span></div>
                            <div><span class="font-semibold">Status:</span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold ${statusColor}">
                                ${statusText}
                                </span>
                            </div>
                        </div>
                    `;
                    document.getElementById('izin-modal-content').innerHTML = html;
                    document.getElementById('izin-modal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                })
                .catch(err => {
                    document.getElementById('izin-modal-content').innerHTML = '<div class="text-red-600">Gagal memuat detail.</div>';
                    document.getElementById('izin-modal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            function closeIzinModal() {
                document.getElementById('izin-modal').classList.add('hidden');
                document.body.style.overflow = '';
            }
        </script>
    </section>
</x-layouts.app>