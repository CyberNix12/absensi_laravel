<x-layouts.app :title="$title">
    <section class="w-full h-full">
        <x-page-title :title="$title" />
        <div class="flex flex-col gap-4">
            <div class="card">
                <div
                    class="card-header flex flex-col justify-center items-start md:justify-between md:items-center gap-2 md:flex-row">
                    <div>
                        <a href="{{ route('karyawan.create') }}" class="btn btn-neutral text-nowrap">
                            <i class="fas fa-plus"></i> Tambah Karyawan
                        </a>
                    </div>
                    <form method="get" action="{{ route('karyawan') }}">
                        <div class="join max-w-sm flex">
                            <input class="input join-item" placeholder="Search" id="keyword" name="keyword"
                                value="{{ $keyword }}" />
                            <select class="input join-item" name="role" id="role">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            </select>
                            <button type="submit" class="btn btn-primary join-item">Search</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="w-full overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Foto</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Telepon</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Terdaftar</th>
                                    <th class="text-center">
                                        <i class="fas fa-cog">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center py-4">Tidak ada data absensi</td>
                                    </tr>
                                @endif
                                @foreach ($datas as $karyawan)
                                    <tr>
                                        <td class="text-center">{{ $datas->firstItem() + $loop->index }}</td>
                                        <td>
                                            <img src="{{ $karyawan->profile_picture_asset }}" alt="Foto Karyawan"
                                                class="size-10 rounded-full object-cover">
                                        </td>
                                        <td class="text-center">{{ $karyawan->username }}</td>
                                        <td class="text-center">{{ $karyawan->name }}</td>
                                        <td class="text-center">{{ $karyawan->phone }}</td>
                                        <td class="text-center">{{ $karyawan->email }}</td>
                                        <td class="text-center">{{ $karyawan->role }}</td>
                                        <td class="text-center">{{ $karyawan->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="flex flex-col justify-center items-center gap-2">
                                                @if (auth()->user()->id === $karyawan->id)
                                                    <a href="{{ route('settings.profile') }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-user fa-fw"></i> My Profile
                                                    </a>
                                                @else
                                                    <a href="{{ route('karyawan.reset-password', $karyawan) }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-lock fa-fw"></i> Reset Password
                                                    </a>
                                                    <a href="{{ route('karyawan.edit', $karyawan) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-pencil fa-fw"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-error"
                                                        onclick="confirmDelete('{{ $karyawan->name }}', '{{ route('karyawan.destroy', $karyawan) }}')">
                                                        <i class="fas fa-trash fa-fw"></i> Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>

    </section>

    @push('modals')
        <div id="modal-detail"
            class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 hidden [--is-layout-affect:true] overlay-backdrop-open:bg-neutral-800/80 "
            role="dialog" tabindex="-1">
            <div class="modal-dialog overlay-open:opacity-100 overlay-open:duration-300">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Detail</h3>
                        <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                            aria-label="Close" onClick="closeModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onClick="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            function confirmDelete(name, url) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        }).then(data => {
                            Swal.fire('Deleted!', 'Data berhasil dihapus.', 'success').then(() => {
                                location.reload();
                            });
                        }).catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        });
                    }
                });
            }
        </script>
    @endpush

</x-layouts.app>