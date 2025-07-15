@extends('layouts.siswa')

@section('content')
<div class="page-heading">
    <h3>Selamat Datang, {{ auth()->user()->siswa->nama ?? 'Siswa' }} 👋</h3>
    <p class="text-subtitle text-muted">Ini adalah ringkasan data akademikmu.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <!-- Mata Pelajaran -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary text-white rounded-circle me-3">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Mata Pelajaran</h6>
                                    <h5 class="mb-0">{{ $totalMapel ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kehadiran -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Kehadiran</h6>
                                    <h5 class="mb-0">{{ $totalAbsensi ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tagihan -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Tagihan</h6>
                                    <h5 class="mb-0">Rp{{ number_format($totalTagihan ?? 0, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-secondary text-white rounded-circle me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Jadwal Hari Ini</h6>
                                    <h5 class="mb-0">{{ $jadwalHariIni ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
