@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Kelas</h3>
                <p class="text-subtitle text-muted">Daftar semua kelas yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                    + Tambah Kelas
                </button>
            </div>
        </div>
    </div>
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
    <section class="section mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Kelas</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tingkat</th>
                            <th>Nomor Kelas</th>
                            <th>Jurusan</th>
                            <th>Kapasitas</th>
                            <th>Wali Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->tingkat ?? '-' }}</td>
                            <td>{{ $item->nomor_kelas }}</td>
                            <td>{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>{{ $item->kapasitas ?? '-' }}</td>
                            <td>{{ $item->wali->nama ?? 'Wali Kelas Belum Ditambahkan' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $item->id }}">Edit</button>
                                <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>

                                <!-- Modal Edit Kelas -->
                                <div class="modal fade" id="modalEditKelas{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditKelasLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditKelasLabel{{ $item->id }}">Edit Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('admin.kelas.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tingkat Kelas</label>
                                                <select name="tingkat" class="form-control" required>
                                                    <option value="">Pilih Tingkat</option>
                                                    <option value="X" {{ (isset($item) && $item->tingkat == 'X') ? 'selected' : '' }}>X</option>
                                                    <option value="XI" {{ (isset($item) && $item->tingkat == 'XI') ? 'selected' : '' }}>XI</option>
                                                    <option value="XII" {{ (isset($item) && $item->tingkat == 'XII') ? 'selected' : '' }}>XII</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Kelas</label>
                                                <input type="text" name="nomor_kelas" class="form-control" value="{{ $item->nomor_kelas }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kapasitas</label>
                                                <input type="number" name="kapasitas" class="form-control" value="{{ $item->kapasitas }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Wali Kelas</label>
                                                <select name="wali_kelas" class="form-control" required>
                                                    <option value="">Pilih Guru</option>
                                                    @foreach ($guru as $g)
                                                        <option value="{{ $g->id }}" {{ $item->wali_kelas == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jurusan</label>
                                                <select name="id_jurusan" class="form-control" required>
                                                    <option value="">Pilih Jurusan</option>
                                                    @foreach ($jurusan as $j)
                                                        <option value="{{ $j->id }}" {{ (isset($item) && $item->id_jurusan == $j->id) ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
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
                            <td colspan="7" class="text-center text-muted">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

<!-- Tambah Kelas -->
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahKelasLabel">Tambah Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tingkat Kelas</label>
            <select name="tingkat" class="form-control" required>
                <option value="">Pilih Tingkat</option>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Kelas</label>
            <input type="text" name="nomor_kelas" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kapasitas</label>
            <input type="number" name="kapasitas" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Wali Kelas</label>
            <select name="wali_kelas" class="form-control" required>
                <option value="">Pilih Guru</option>
                @foreach ($guru as $g)
                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <select name="id_jurusan" class="form-control" required>
                <option value="">Pilih Jurusan</option>
                @foreach ($jurusan as $j)
                    <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
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
        const table1 = document.querySelector('#table1');
        if (table1) {
            new simpleDatatables.DataTable(table1);
        }
    });
</script>
@endpush
