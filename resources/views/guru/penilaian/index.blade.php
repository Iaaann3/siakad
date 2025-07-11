@extends('layouts.guru')
@section('content')
<div class="container-fluid p-0">
    <div class="card mt-2">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Daftar Penilaian Siswa</h5>

            <!-- Tombol Tambah -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPenilaian">
                + Tambah Penilaian
            </button>

            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Jenis Penilaian</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilaian as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->siswa->nama ?? '-' }}</td>
                        <td>{{ $item->siswa->kelas->nomor_kelas ?? '-' }}</td>
                        <td>{{ $item->semester->tahun_ajaran?? '-' }}</td>
                        <td>{{ ucfirst($item->jenis_penilaian) }}</td>
                        <td>{{ number_format($item->nilai, 2) }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditPenilaian{{ $item->id }}">Edit</a>
                        </td>
                    </tr>

                    <!-- Modal Edit Penilaian -->
                    <div class="modal fade" id="modalEditPenilaian{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $item->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <form action="{{ route('guru.penilaian.update', $item->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="modalEditLabel{{ $item->id }}">Edit Penilaian</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Nilai</label>
                                <input type="number" step="0.01" name="nilai" class="form-control" value="{{ $item->nilai }}" required min="0" max="100">
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control">{{ $item->keterangan }}</textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data penilaian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
             @if (session('success'))
        <div class="alert alert-light-success color-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-light-danger color-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Penilaian -->
<div class="modal fade" id="modalTambahPenilaian" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('guru.penilaian.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahLabel">Tambah Penilaian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Siswa</label>
            <select name="id_siswa" class="form-select" required>
              <option value=""> Pilih Siswa </option>
              @foreach($siswaList as $siswa)
              <option value="{{ $siswa->id }}">{{ $siswa->nama }} (Kelas {{ $siswa->kelas->nomor_kelas }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Semester</label>
            <select name="id_semester" class="form-select" required>
              <option value=""> Pilih Semester </option>
              @foreach($semesterList as $semester)
              <option value="{{ $semester->id }}">{{ $semester->tahun_ajaran }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Penilaian</label>
            <select name="jenis_penilaian" class="form-select" required>
              <option value="">Pilih Jenis Penilaian</option>
              <option value="harian">Harian</option>
              <option value="uts">UTS</option>
              <option value="uas">UAS</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nilai</label>
            <input type="number" step="0.01" name="nilai" class="form-control" required min="0" max="100">
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
