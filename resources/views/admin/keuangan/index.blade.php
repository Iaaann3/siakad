@extends('layouts.admin')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Keuangan</h3>
                <p class="text-subtitle text-muted">Daftar pembayaran keuangan siswa.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKeuangan">
                    + Tambah Pembayaran
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

    <section class="section mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Pembayaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tableKeuangan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->siswa->nama ?? '-' }}</td>
                            <td>{{ $item->jenisKeuangan->nama ?? '-' }}</td>
                            <td>{{ $item->tanggal_bayar }}</td>
                            <td>Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Keuangan -->
<div class="modal fade" id="modalTambahKeuangan" tabindex="-1" aria-labelledby="modalTambahKeuanganLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.keuangan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahKeuanganLabel">Tambah Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Siswa</label>
              <select name="id_siswa" class="form-select" required>
                <option value="">Pilih Siswa</option>
                @foreach ($siswa as $item)
                  <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jenis Keuangan</label>
              <select name="id_jenis_keuangan" class="form-select" required>
                <option value=""> Pilih Jenis </option>
                @foreach ($jenis as $item)
                  <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Semester</label>
              <select name="id_semester" class="form-select" required>
                <option value="">-- Pilih Semester --</option>
                @foreach ($semester as $item)
                  <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Tanggal Bayar</label>
              <input type="date" name="tanggal_bayar" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jumlah Bayar</label>
              <input type="number" step="0.01" name="jumlah_bayar" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Metode Pembayaran</label>
              <select name="metode_pembayaran" class="form-select" required>
                <option value=""> Pilih Metode </option>
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="lunas">Lunas</option>
                <option value="belum_lunas">Belum Lunas</option>
              </select>
            </div>
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
        const table = document.querySelector('#tableKeuangan');
        if (table) {
            new simpleDatatables.DataTable(table);
        }
    });
</script>
@endpush
