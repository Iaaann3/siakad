@extends('layouts.guru')
@section('content')
<div class="container-fluid p-0">
    <div class="card mt-2">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Daftar Absensi Siswa</h5>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAbsensi">
                + Tambah Absensi
            </button>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jadwal</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->siswa->nama ?? '-' }}</td>
                        <td>{{ $item->siswa->kelas->nomor_kelas ?? '-' }}</td>
                        <td>{{ $item->jadwal->mapel->nama_mapel ?? '-' }} ({{ $item->jadwal->hari ?? '-' }})</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditAbsensi{{ $item->id }}">Edit</a>
                        </td>
                    </tr>
                    <!-- Modal Edit Absensi -->
                    <div class="modal fade" id="modalEditAbsensi{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $item->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <form action="{{ route('guru.absensi.update', $item->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="modalEditLabel{{ $item->id }}">Edit Absensi</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                  <option value="hadir" {{ $item->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                  <option value="izin" {{ $item->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                  <option value="sakit" {{ $item->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                  <option value="alpha" {{ $item->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
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
                        <td colspan="7" class="text-center text-muted">Belum ada data absensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Tambah Absensi -->
<div class="modal fade" id="modalTambahAbsensi" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('guru.absensi.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahLabel">Tambah Absensi</h5>
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
            <label class="form-label">Jadwal</label>
            <select name="id_jadwal" class="form-select" required>
              <option value=""> Pilih Jadwal </option>
              @foreach($jadwalList as $jadwal)
              <option value="{{ $jadwal->id }}">{{ $jadwal->mapel->nama_mapel ?? '-' }} ({{ $jadwal->hari ?? '-' }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="hadir">Hadir</option>
              <option value="izin">Izin</option>
              <option value="sakit">Sakit</option>
              <option value="alpha">Alpha</option>
            </select>
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
