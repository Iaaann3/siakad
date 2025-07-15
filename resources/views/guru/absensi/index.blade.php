@extends('layouts.guru')

@section('content')
<div class="container-fluid p-0">
    <h4 class="fw-semibold mt-2 mb-3">Absensi Siswa</h4>

    <form method="GET">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="jadwal">Pilih Mapel / Jadwal</label>
                <select name="jadwal" id="jadwal" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Jadwal</option>
                    @foreach($jadwalList as $j)
                        <option value="{{ $j->id }}" {{ request('jadwal') == $j->id ? 'selected' : '' }}>
                            {{ $j->mapel->nama_mapel }} - Kelas {{ $j->kelas->nomor_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if($selectedJadwal && $siswaList->count())
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nama Siswa</th>
                    @for($p=1; $p<=20; $p++)
                        <th>Pertemuan {{ $p }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($siswaList as $siswa)
                <tr>
                    <td>{{ $siswa->nama }}</td>
                    @for($p=1; $p<=20; $p++)
                        @php
                            $absen = $absensi[$siswa->id][$p] ?? null;
                            $status = $absen ? ucfirst($absen->status) : '-';
                            $color = match(strtolower($status)) {
                                'hadir' => 'success',
                                'sakit' => 'warning',
                                'izin'  => 'info',
                                'alfa'  => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <td>
                            <span class="badge bg-{{ $color }} absen-editable"
                                data-jadwal="{{ $selectedJadwal->id }}"
                                data-siswa="{{ $siswa->id }}"
                                data-pertemuan_ke="{{ $p }}" 
                                style="cursor:pointer;">
                                {{ $status }}
                            </span>
                        </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.absen-editable').forEach(function(el) {
        el.addEventListener('click', function() {
            let statusList = ['hadir', 'sakit', 'izin', 'alpha'];
            let currentDisplay = el.textContent.trim();
            let currentInternal = currentDisplay.toLowerCase();

            let nextDisplay = prompt('Status Kehadiran (Hadir/Sakit/Izin/Alfa):', currentDisplay);
            if (nextDisplay) {
                let nextInternal = nextDisplay.toLowerCase();

                if (statusList.includes(nextInternal)) {
                    fetch('{{ route("guru.absensi.ajaxUpdate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            jadwal_id: el.dataset.jadwal,
                            siswa_id: el.dataset.siswa,
                            pertemuan_ke: el.dataset.pertemuan_ke, 
                            status: nextInternal
                        })
                    }).then(res => res.json()).then(data => {
                        if(data.success) {
                            el.textContent = nextDisplay;
                            let colorMap = {
                                'hadir': 'success',
                                'sakit': 'warning',
                                'izin': 'info',
                                'alpha': 'danger'
                            };
                            el.className = 'badge bg-' + (colorMap[nextInternal] || 'secondary') + ' absen-editable';
                        } else {
                            alert('Gagal update absensi! ' + (data.message || ''));
                        }
                    }).catch(error => {
                        console.error('Error during fetch:', error);
                        alert('Terjadi kesalahan jaringan atau server tidak merespons.');
                    });
                } else {
                    alert('Status tidak valid. Harap masukkan Hadir, Sakit, Izin, atau Alpha.');
                }
            }
        });
    });
});
</script>
@endsection
