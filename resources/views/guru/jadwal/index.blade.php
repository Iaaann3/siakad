@extends('layouts.guru')
@section('content')
<div class="container-fluid p-0">
    <div class="card mt-2">
        <div class="card-body">
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->kelas->nomor_kelas ?? '-' }}</td>
                        <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->hari }}</td>
                        <td>{{ $item->jam_mulai ?? '-' }}</td>
                        <td>{{ $item->jam_selesai ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data jadwal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('guru.jadwal.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="id_kelas" class="form-select" required>
              <option value="">Pilih Kelas</option>
              @foreach($kelasList as $kelas)
              <option value="{{ $kelas->id }}">{{ $kelas->nomor_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Mapel</label>
            <select name="id_mapel" class="form-select" required>
              <option value="">Pilih Mapel</option>
              @foreach($mapelList as $mapel)
              <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            <select name="hari" class="form-select" required>
              <option value="">Pilih Hari</option>
              @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
              <option value="{{ $hari }}">{{ $hari }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" required>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
