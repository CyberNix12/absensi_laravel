<x-layouts.app>
    <section class="w-full h-full">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <x-page-title title="Ajukan Izin/Cuti/Sakit" />
    
            {{-- Tampilkan nama user login --}}
            
    
            <form action="{{ route('pengajuan-izin.store') }}" method="POST" class="max-w-lg space-y-5">
                <div class="mb-4 p-3 rounded bg-base-50 border">
                    <span class="font-semibold">Nama:</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                @csrf
    
                <div>
                    <label for="tanggal_mulai" class="block mb-1 font-medium">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="input input-bordered w-full"
                        value="{{ old('tanggal_mulai') }}" required>
                </div>
                <div>
                    <label for="tanggal_selesai" class="block mb-1 font-medium">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="input input-bordered w-full"
                        value="{{ old('tanggal_selesai') }}" required>
                </div>
                <div>
                    <label for="jenis" class="block mb-1 font-medium">Jenis</label>
                    <select id="jenis" name="jenis" class="select select-bordered w-full" required>
                        <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
                <div>
                    <label for="alasan" class="block mb-1 font-medium">Alasan</label>
                    <textarea id="alasan" name="alasan" class="textarea textarea-bordered w-full" required>{{ old('alasan') }}</textarea>
                </div>
    
                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            </form>
        </div>
    </section>
    </x-layouts.app>