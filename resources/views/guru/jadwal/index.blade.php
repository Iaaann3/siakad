@extends('layouts.guru')
@section('content')
<div class="container-fluid p-0">
    <h4 class="fw-semibold mt-2 mb-3">Daftar Jadwal Mengajar</h4>

    <div class="row">
        @forelse($jadwal as $item)
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card shadow-sm" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title mb-1">Kelas {{ $item->kelas->nomor_kelas ?? '-' }}</h5>
                    <p class="card-text mb-1">
                        Mapel
                        <span class="text-success">{{ $item->mapel->nama_mapel ?? '-' }}</span>
                    </p>
                    <p class="card-text mb-1">
                        Hari
                        <span class="{{ $item->hari == 'Senin' ? 'text-primary fw-bold' : '' }}">
                            {{ $item->hari }}
                        </span>
                    </p>
                    <p class="card-text mb-0">
                        Jam {{ $item->jam_mulai ?? '-' }} - {{ $item->jam_selesai ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Belum ada data jadwal.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
