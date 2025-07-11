@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Jurusan</h3>
                <p class="text-subtitle text-muted">Daftar semua jurusan yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                    + Tambah Jurusan
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
                <h5 class="card-title">Daftar Jurusan</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jurusan as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->nama_jurusan }}</td>
                            <td>

                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditJurusan{{ $item->id }}">Edit</button>
                                <form action="{{ route('admin.jurusan.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                <!-- Modal Edit Jurusan -->
                                <div class="modal fade" id="modalEditJurusan{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditJurusanLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditJurusanLabel{{ $item->id }}">Edit Jurusan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('admin.jurusan.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                          <div class="mb-3">
                                            <label for="nama_jurusan_edit_{{ $item->id }}" class="form-label">Nama Jurusan</label>
                                            <input type="text" name="nama_jurusan" id="nama_jurusan_edit_{{ $item->id }}" class="form-control" value="{{ $item->nama_jurusan }}" required>
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
                            <td colspan="3" class="text-center text-muted">Belum ada data jurusan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

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

<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-labelledby="modalTambahJurusanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahJurusanLabel">Tambah Jurusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
            <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control @error('nama_jurusan') is-invalid @enderror" value="{{ old('nama_jurusan') }}" required>
            @error('nama_jurusan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
