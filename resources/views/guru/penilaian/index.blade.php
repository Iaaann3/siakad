@extends('layouts.guru')

@section('content')
<div class="container-fluid p-0">
  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Daftar Nilai Siswa</h5>
    </div>

    <!-- Filter Kelas & Semester -->
    <form method="GET" class="row g-2 mb-3 px-3">
      <div class="col-md-4">
        <select name="kelas" class="form-select" required>
          <option value="">Pilih Kelas</option>
          @foreach($kelasList as $kelas)
            <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
              Kelas {{ $kelas->nomor_kelas }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <select name="semester" class="form-select" required>
          <option value="">Pilih Semester</option>
          @foreach($semesterList as $semester)
            <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
              {{ $semester->tahun_ajaran }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
  <select name="mapel" class="form-select" required>
    <option value="">Pilih Mapel</option>
    @foreach($mapelList as $mapel)
      <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
        {{ $mapel->nama_mapel }}
      </option>
    @endforeach
  </select>
</div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100" type="submit">Filter</button>
      </div>
    </form>

    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NH1</th>
            <th>NH2</th>
            <th>NH3</th>
            <th>Rata-rata</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Rapot</th>
          </tr>
        </thead>
        <tbody>
          @forelse($siswaList as $no => $siswa)
          @php $nilai = $penilaianMap[$siswa->id] ?? null; @endphp
          <tr data-row-id="{{ $nilai->id ?? 0 }}">
            <td>{{ $no + 1 }}</td>
            <td>{{ $siswa->nama }}</td>
            <td class="editable" data-id="{{ $nilai->id ?? 0 }}" data-siswa="{{ $siswa->id }}" data-field="nh1">{{ $nilai->nh1 ?? '-' }}</td>
            <td class="editable" data-id="{{ $nilai->id ?? 0 }}" data-siswa="{{ $siswa->id }}" data-field="nh2">{{ $nilai->nh2 ?? '-' }}</td>
            <td class="editable" data-id="{{ $nilai->id ?? 0 }}" data-siswa="{{ $siswa->id }}" data-field="nh3">{{ $nilai->nh3 ?? '-' }}</td>
            <td class="rata-rata">{{ isset($nilai->rata_rata) ? number_format($nilai->rata_rata, 2) : '0' }}</td>
            <td class="editable" data-id="{{ $nilai->id ?? 0 }}" data-siswa="{{ $siswa->id }}" data-field="uts">{{ $nilai->uts ?? '-' }}</td>
            <td class="editable" data-id="{{ $nilai->id ?? 0 }}" data-siswa="{{ $siswa->id }}" data-field="uas">{{ $nilai->uas ?? '-' }}</td>
            <td class="rapot">{{ $nilai->rapot ?? '-' }}</td>
          </tr>
          @empty
            <tr>
              <td colspan="9" class="text-muted text-center">Belum ada data penilaian.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const mapelId = '{{ request("mapel") }}';

  document.querySelectorAll('.editable').forEach(cell => {
    cell.addEventListener('dblclick', function () {
      if (cell.querySelector('input')) return;

      const id = cell.dataset.id;
      const field = cell.dataset.field;
      const siswaId = cell.dataset.siswa;
      const kelasId = '{{ request("kelas") }}';
      const semesterId = '{{ request("semester") }}';
      const originalValue = cell.textContent.trim();

      const input = document.createElement('input');
      input.type = 'text';
      input.value = originalValue === '-' ? '' : originalValue;
      input.className = 'form-control form-control-sm';
      input.style.width = '100%';
      cell.textContent = '';
      cell.appendChild(input);
      input.focus();

      const saveValue = () => {
        const newValue = input.value.trim();
        if (!newValue) {
          cell.textContent = originalValue;
          return;
        }

        let fetchOptions = {
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        };

        // INSERT nilai baru
        if (!id || id === '0') {
          fetchOptions.method = 'POST';
          fetchOptions.body = JSON.stringify({
            id_siswa: siswaId,
            id_kelas: kelasId,
            id_semester: semesterId,
            id_mapel: mapelId, // wajib dikirim untuk insert baru
            [field]: newValue
          });

          fetch('/guru/penilaian', fetchOptions)
            .then(async res => {
              const raw = await res.text();
              try {
                const data = JSON.parse(raw);
                if (data.success && data.id) {
                  cell.dataset.id = data.id;
                  cell.textContent = newValue || '0';
                  updateRow(data.id, cell.closest('tr'));
                } else {
                  cell.textContent = originalValue;
                  alert(data.message || 'Gagal menyimpan');
                }
              } catch (e) {
                cell.textContent = originalValue;
                alert('Server Error: ' + raw.substring(0, 100));
              }
            });

        // UPDATE nilai lama
        } else {
          fetchOptions.method = 'PUT';
          fetchOptions.body = JSON.stringify({ [field]: newValue });

          fetch(`/guru/penilaian/${id}`, fetchOptions)
            .then(async res => {
              const raw = await res.text();
              try {
                const data = JSON.parse(raw);
                if (data.success) {
                  cell.textContent = newValue || '0';
                  updateRow(id, cell.closest('tr'));
                } else {
                  cell.textContent = originalValue;
                  alert(data.message || 'Gagal update');
                }
              } catch (e) {
                cell.textContent = originalValue;
                alert('Server Error: ' + raw.substring(0, 100));
              }
            });
        }
      };

      input.addEventListener('blur', saveValue);
      input.addEventListener('keypress', e => {
        if (e.key === 'Enter') input.blur();
      });
    });
  });
});

// Update nilai rata-rata dan rapot pada baris terkait
function updateRow(id, row) {
  fetch(`/guru/penilaian/${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.rata_rata !== undefined && data.rapot !== undefined) {
        row.querySelector('.rata-rata').textContent = data.rata_rata;
        row.querySelector('.rapot').textContent = data.rapot;
      }
    });
}
</script>
@endpush

