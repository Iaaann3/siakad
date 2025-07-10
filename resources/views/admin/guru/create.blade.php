@extends('layouts.admin')

@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Tambah Guru</h3>
        <p class="text-subtitle text-muted">Form untuk menambah guru baru.</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first text-end">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>

  <section class="section mt-3">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Form Tambah Guru</h4>
          </div>
          <div class="card-body">

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="id_users" class="form-label">Akun Pengguna</label>
                <select name="id_users" id="id_users" class="form-select @error('id_users') is-invalid @enderror" required>
                  <option value="">Pilih User</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('id_users') == $user->id ? 'selected' : '' }}>
                      {{ $user->name }} ({{ $user->email }})
                    </option>
                  @endforeach
                </select>
                @error('id_users')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- NAMA --}}
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Guru</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- NO TELEPON --}}
              <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                @error('no_telepon')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="id_mapel" class="form-label">Mata Pelajaran</label>
                <select name="id_mapel" id="id_mapel" class="form-select @error('id_mapel') is-invalid @enderror" required>
                  <option value="">Pilih Mapel</option>
                  @foreach ($mapel as $m)
                    <option value="{{ $m->id }}" {{ old('id_mapel') == $m->id ? 'selected' : '' }}>
                      {{ $m->nama_mapel }}
                    </option>
                  @endforeach
                </select>
                @error('id_mapel')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- FOTO --}}
              <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror">
                @error('foto')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
