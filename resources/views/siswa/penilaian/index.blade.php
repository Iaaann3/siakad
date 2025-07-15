@extends('layouts.siswa')
@section('content')
<div class="container mt-4">
    <h2>Penilaian Siswa</h2>
    <div class="mb-3">
        <label for="mapelSelect" class="form-label">Pilih Mata Pelajaran:</label>
        <select id="mapelSelect" class="form-select">
            <option value=""> Pilih Mapel </option>
            @foreach($mapelList as $mapel)
                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
            @endforeach

        </select>
    </div>

    <div id="detailNilai" style="display: none;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NH1</th><th>NH2</th><th>NH3</th>
                    <th>UTS</th><th>UAS</th>
                    <th>Rata-rata</th><th>Rapot</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="nh1">-</td><td id="nh2">-</td><td id="nh3">-</td>
                    <td id="uts">-</td><td id="uas">-</td>
                    <td id="rata">-</td><td id="rapot">-</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('mapelSelect').addEventListener('change', function () {
        const mapelId = this.value;
        if (!mapelId) {
            document.getElementById('detailNilai').style.display = 'none';
            return;
        }

        fetch(`/siswa/penilaian-siswa/detail/${mapelId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('nh1').innerText = data.nh1;
                document.getElementById('nh2').innerText = data.nh2;
                document.getElementById('nh3').innerText = data.nh3;
                document.getElementById('uts').innerText = data.uts;
                document.getElementById('uas').innerText = data.uas;
                document.getElementById('rata').innerText = data.rata_rata;
                document.getElementById('rapot').innerText = data.rapot;
                document.getElementById('detailNilai').style.display = '';
            })
            .catch(() => {
                alert('Gagal memuat detail nilai.');
                document.getElementById('detailNilai').style.display = 'none';
            });
    });
</script>
@endpush
