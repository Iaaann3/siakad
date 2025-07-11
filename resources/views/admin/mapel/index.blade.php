@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Mapel</h3>
                <p class="text-subtitle text-muted">Daftar semua mata pelajaran yang tersedia.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary">+ Tambah Mapel</a>
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
                <h5 class="card-title">Daftar Mata Pelajaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mapel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapel as $no => $item)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->nama_mapel }}</td>
                            <td>
                                <a href="{{ route('admin.mapel.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.mapel.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus mapel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data mapel.</td>
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
