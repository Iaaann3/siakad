@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Jadwal</h3>
                <p class="text-subtitle text-muted">Daftar semua jadwal pelajaran yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                    + Tambah Jadwal
                </button>
            </div>
        </div>
    </div>
    <section class="section mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Jadwal</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tableJadwal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Guru</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwal as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->kelas->nomor_kelas ?? '-' }}</td>
                            <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                            <td>{{ $item->guru->nama ?? '-' }}</td>
                            <td>{{ $item->hari }}</td>
                            <td>{{ $item->jam_mulai ?? '-' }}</td>
                            <td>{{ $item->jam_selesai ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditJadwal{{ $item->id }}">Edit</button>
                                <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                <!-- Modal Edit Jadwal -->
                                <div class="modal fade" id="modalEditJadwal{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditJadwalLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditJadwalLabel{{ $item->id }}">Edit Jadwal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('admin.jadwal.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kelas</label>
                                                <select name="id_kelas" class="form-control" required>
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach ($item->kelas::all() as $k)
                                                        <option value="{{ $k->id }}" {{ $item->id_kelas == $k->id ? 'selected' : '' }}>{{ $k->nomor_kelas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Mapel</label>
                                                <select name="id_mapel" class="form-control" required>
                                                    <option value="">Pilih Mapel</option>
                                                    @foreach ($item->mapel::all() as $m)
                                                        <option value="{{ $m->id }}" {{ $item->id_mapel == $m->id ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Guru</label>
                                                <select name="id_guru" class="form-control" required>
                                                    <option value="">Pilih Guru</option>
                                                    @foreach ($item->guru::all() as $g)
                                                        <option value="{{ $g->id }}" {{ $item->id_guru == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Hari</label>
                                                <select name="hari" class="form-control" required>
                                                    <option value="">Pilih Hari</option>
                                                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                                                        <option value="{{ $hari }}" {{ $item->hari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jam Mulai</label>
                                                <input type="time" name="jam_mulai" class="form-control" value="{{ $item->jam_mulai }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jam Selesai</label>
                                                <input type="time" name="jam_selesai" class="form-control" value="{{ $item->jam_selesai }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                          <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data jadwal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

<!-- Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.jadwal.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option value="">Pilih Kelas</option>
                @foreach (App\Models\Kelas::all() as $k)
                    <option value="{{ $k->id }}">{{ $k->nomor_kelas }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Mapel</label>
            <select name="id_mapel" class="form-control" required>
                <option value="">Pilih Mapel</option>
                @foreach (App\Models\Mapel::all() as $m)
                    <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Guru</label>
            <select name="id_guru" class="form-control" required>
                <option value="">Pilih Guru</option>
                @foreach (App\Models\Guru::all() as $g)
                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            <select name="hari" class="form-control" required>
                <option value="">Pilih Hari</option>
                @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableJadwal = document.querySelector('#tableJadwal');
        if (tableJadwal) {
            new simpleDatatables.DataTable(tableJadwal);
        }
    });
</script>
@endpush
