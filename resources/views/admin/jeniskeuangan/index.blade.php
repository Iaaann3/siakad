@extends('layouts.admin')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Jenis Keuangan</h3>
                <p class="text-subtitle text-muted">Daftar jenis keuangan yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
                    + Tambah Jenis
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

    @if ($errors->any())
    <div class="alert alert-light-danger color-danger alert-dismissible fade show mt-3" role="alert">
        <h6><i class="bi bi-x-circle"></i> Terjadi kesalahan:</h6>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-light-danger color-danger">
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
                <h5 class="card-title">Daftar Jenis Keuangan</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tableJenis">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenis as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>
                                <a href="{{ route('admin.jeniskeuangan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.jeniskeuangan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data jenis keuangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Jenis Keuangan -->
<div class="modal fade" id="modalTambahJenis" tabindex="-1" aria-labelledby="modalTambahJenisLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahJenisLabel">Tambah Jenis Keuangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.jeniskeuangan.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="2"></textarea>
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
        const tableJenis = document.querySelector('#tableJenis');
        if (tableJenis) {
            new simpleDatatables.DataTable(tableJenis);
        }
    });
</script>
@endpush
