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
                    <form method="post" action="{{ route('data-presensi.in.store') }}" enctype="multipart/form-data"
                        class="space-y-3" onsubmit="disableSubmit(this)">
                        @csrf
                        <div>
                            <label class="label-text" for="location">Lokasi</label>
                            <input type="text" id="location" name="location" placeholder="Lokasi" class="input"
                                required readonly />
                            <button type="button" class="btn btn-secondary mt-2" id="get-location">
                                <i class="fas fa-map"></i> Update Lokasi
                            </button>
                        </div>
                        <div>
                            <label class="label-text" for="foto">Foto</label>
                            <input type="file" id="foto" name="foto" class="input" capture="camera"
                                required />
                        </div>

                        <hr />

                        <div id="results"
                            class="min-h-36 dark:text-white h-auto max-w-lg text-black border rounded-lg">
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" class="btn btn-primary w-full md:w-auto">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('data-presensi') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            const locationInput = document.querySelector('#location');
            const getLocation = document.querySelector('#get-location');

            document.addEventListener('DOMContentLoaded', function() {
                getLola();
            });

            getLocation.addEventListener('click', function() {
                getLola();
            });

            function getLola() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        locationInput.value = `${latitude}, ${longitude}`;
                    }, function(error) {
                        console.error('Error getting location:', error);
                    });
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            }

            const foto = document.getElementById('foto');
            const results = document.getElementById('results');

            foto.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        const img = document.createElement('img');
                        img.src = reader.result;
                        img.className = 'w-full h-full object-cover';
                        results.innerHTML = '';
                        results.appendChild(img);
                    }

                    reader.readAsDataURL(file);
                }
            });

            function disableSubmit(form) {
                const buttons = form.querySelectorAll('button[type="submit"]');
                buttons.forEach(button => {
                    button.disabled = true;
                    button.innerHTML = `<span class="loading loading-infinity loading-xl"></span> Processing...`;
                });
            }
        </script>
    @endpush
</x-layouts.app>