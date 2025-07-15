@extends('layouts.siswa')
@section('content')
<div class="container-fluid p-0">
    <h4 class="fw-semibold mt-2 mb-3">Absensi Saya</h4>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Kalender Absensi</h5>
            @php
                use Carbon\Carbon;
                $now = Carbon::now();
                $year = $now->year;
                $month = $now->month;
                $daysInMonth = $now->daysInMonth;
                $firstDay = Carbon::create($year, $month, 1)->dayOfWeekIso; // 1=Senin
                $absensiMap = [];
                $rekap = ['hadir'=>0, 'sakit'=>0, 'izin'=>0, 'alpha'=>0];
                foreach($absensi as $a) {
                    $tgl = Carbon::parse($a->tanggal)->day;
                    $absensiMap[$tgl] = strtolower($a->status);
                    if(isset($rekap[$absensiMap[$tgl]])) $rekap[$absensiMap[$tgl]]++;
                }
                $colorMap = [
                    'hadir' => 'success',
                    'sakit' => 'warning',
                    'izin'  => 'info',
                    'alpha' => 'danger',
                ];
            @endphp
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th><th>Min</th>
                    </tr>
                </thead>
                <tbody>
                    @php $day = 1; @endphp
                    @for($row=0; $row < ceil(($daysInMonth + $firstDay - 1)/7); $row++)
                    <tr>
                        @for($col=1; $col<=7; $col++)
                            @if($row==0 && $col < $firstDay)
                                <td></td>
                            @elseif($day > $daysInMonth)
                                <td></td>
                            @else
                                @php
                                    $status = $absensiMap[$day] ?? null;
                                    $color = $status ? $colorMap[$status] : 'secondary';
                                @endphp
                                <td>
                                    <span class="badge bg-{{ $color }}" style="width:2.5em; height:2.5em; display:inline-block; font-size:1em;">
                                        {{ $day }}
                                    </span>
                                    @if($status)
                                        <div style="font-size:0.8em;" class="text-{{ $color }}">{{ ucfirst($status) }}</div>
                                    @endif
                                </td>
                                @php $day++; @endphp
                            @endif
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
            <div class="mt-3">
                <h6>Rekap Absensi Bulan Ini</h6>
                <ul class="list-inline">
                    <li class="list-inline-item"><span class="badge bg-success">Hadir {{ $rekap['hadir'] }}</span></li>
                    <li class="list-inline-item"><span class="badge bg-warning">Sakit {{ $rekap['sakit'] }}</span></li>
                    <li class="list-inline-item"><span class="badge bg-info">Izin {{ $rekap['izin'] }}</span></li>
                    <li class="list-inline-item"><span class="badge bg-danger">Alpha {{ $rekap['alpha'] }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
