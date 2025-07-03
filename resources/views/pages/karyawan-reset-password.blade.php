<x-layouts.app :title="$title">
    <section class="w-full h-full">
        <x-page-title :title="$title" />
        <div class="flex flex-col w-full md:w-sm mx-auto">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action={{ route('karyawan.reset-password-proses', $user) }}
                        enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @method('patch')
                        <div>
                            <label class="label-text" for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Username" class="input"
                                value="{{ $user->username }}" disabled />
                        </div>
                        <div>
                            <label class="label-text" for="password">New Password</label>
                            <input type="password" id="password" name="password" placeholder="Password" class="input"
                                required />
                        </div>
                        <div>
                            <label class="label-text" for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Konfirmasi Password" class="input" required />
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" class="btn btn-primary w-full md:w-auto">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('karyawan') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

</x-layouts.app>
