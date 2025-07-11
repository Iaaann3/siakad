@extends('layouts.admin')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Semester</h3>
                <p class="text-subtitle text-muted">Daftar semua semester yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSemester">
                    + Tambah Semester
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


    @if ($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
    @endif

    <section class="section mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Semester</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tableSemester">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semester as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->tahun_ajaran }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditSemester{{ $item->id }}">Edit</button>
                                <form action="{{ route('admin.semester.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus semester ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEditSemester{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditSemesterLabel{{ $item->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditSemesterLabel{{ $item->id }}">Edit Semester</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('admin.semester.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tahun Ajaran</label>
                                                <input type="text" name="tahun_ajaran" class="form-control" value="{{ $item->tahun_ajaran }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Mulai</label>
                                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ $item->tanggal_mulai }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Selesai</label>
                                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ $item->tanggal_selesai }}" required>
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
                            <td colspan="5" class="text-center text-muted">Belum ada data semester.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Semester -->
<div class="modal fade" id="modalTambahSemester" tabindex="-1" aria-labelledby="modalTambahSemesterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahSemesterLabel">Tambah Semester</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.semester.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control" required>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableSemester = document.querySelector('#tableSemester');
        if (tableSemester) {
            new simpleDatatables.DataTable(tableSemester);
        }
    });
</script>
@endpush
