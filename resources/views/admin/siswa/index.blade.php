@extends('layouts.admin')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Siswa</h3>
                <p class="text-subtitle text-muted">Daftar semua siswa yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                    + Tambah Siswa
                </button>
            </div>
        </div>
    </div>
    @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
  </div>
@endif
    <section class="section mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tableSiswa">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>No Telepon</th>
                            <th>Jurusan</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswa as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kelas->nomor_kelas ?? '-' }}</td>
                            <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $item->no_telepon }}</td>
                            <td>{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Siswa" width="40" class="rounded-circle">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $item->id }}">Edit</button>
                                <form action="{{ route('admin.siswa.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                <!-- Modal Edit Siswa -->
                                <div class="modal fade" id="modalEditSiswa{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditSiswaLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditSiswaLabel{{ $item->id }}">Edit Siswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('admin.siswa.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">User</label>
                                                <select name="id_users" class="form-control" required>
                                                    <option value="">Pilih User</option>
                                                    @foreach ($users as $u)
                                                        <option value="{{ $u->id }}" {{ $item->id_users == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelas</label>
                                                <select name="id_kelas" class="form-control" required>
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach ($kelas as $k)
                                                        @if($k->id_jurusan)
                                                            <option value="{{ $k->id }}" {{ $item->id_kelas == $k->id ? 'selected' : '' }} data-jurusan="{{ $k->jurusan->nama_jurusan ?? '' }}">{{ $k->nomor_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIS</label>
                                                <input type="text" name="nis" class="form-control" value="{{ $item->nis }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea name="alamat" class="form-control">{{ $item->alamat }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="form-control" required>
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No Telepon</label>
                                                <input type="text" name="no_telepon" class="form-control" value="{{ $item->no_telepon }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Foto</label>
                                                <input type="file" name="foto" class="form-control">
                                                @if($item->foto)
                                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Siswa" width="80" class="mt-2">
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jurusan</label>
                                                <select name="id_jurusan" class="form-control" required>
                                                    <option value="">Pilih Jurusan</option>
                                                    @foreach ($jurusan as $j)
                                                        @php
                                                            $adaKelas = $kelas->where('id_jurusan', $j->id)->count() > 0;
                                                        @endphp
                                                        @if($adaKelas)
                                                            <option value="{{ $j->id }}" {{ $item->id_jurusan == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
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
                            <td colspan="9" class="text-center text-muted">Belum ada data siswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

<!-- Tambah Siswa -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahSiswaLabel">Tambah Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">User</label>
            <select name="id_users" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option value="">Pilih Kelas</option>
                @foreach ($kelas as $k)
                    @if($k->id_jurusan)
                        <option value="{{ $k->id }}" data-jurusan="{{ $k->jurusan->nama_jurusan ?? '' }}">{{ $k->nomor_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})</option>
                    @endif
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">No Telepon</label>
            <input type="text" name="no_telepon" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <select name="id_jurusan" class="form-control" required>
                <option value="">Pilih Jurusan</option>
                @foreach ($jurusan as $j)
                    @php
                        // Cek apakah jurusan ini ada di kelas manapun
                        $adaKelas = $kelas->where('id_jurusan', $j->id)->count() > 0;
                    @endphp
                    @if($adaKelas)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                    @endif
                @endforeach
            </select>
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
        const tableSiswa = document.querySelector('#tableSiswa');
        if (tableSiswa) {
            new simpleDatatables.DataTable(tableSiswa);
        }
    });
</script>
@endpush
